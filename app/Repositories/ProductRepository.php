<?php

namespace App\Repositories;

/**
 * ProductRepository
 *
 * Handles all file I/O for the product JSON data store.
 *
 * Locking strategy:
 *   - READ  (findAll, findById): open with 'r', acquire LOCK_SH (shared),
 *     read contents, release lock. Multiple readers can hold LOCK_SH
 *     simultaneously; a writer must wait for all readers to finish.
 *
 *   - WRITE (save): open with 'c' (create if absent, NO immediate truncation),
 *     acquire LOCK_EX (exclusive), THEN truncate with ftruncate() + rewind(),
 *     write JSON, release lock.
 *
 *     Why 'c' and not 'w'?
 *     'w' truncates the file the moment fopen() is called — before the lock
 *     is acquired. This creates a race window where a concurrent reader opens
 *     the file after truncation but before the new content is written, and
 *     sees an empty file. 'c' avoids this by deferring truncation until after
 *     the exclusive lock is held.
 */
class ProductRepository
{
    /**
     * Absolute path to the JSON data file.
     */
    private string $storagePath;

    public function __construct()
    {
        $this->storagePath = storage_path('products.json');
    }

    /**
     * Read all products from the JSON file.
     *
     * Opens the file with a shared (read) lock so multiple concurrent readers
     * are allowed. Returns an empty array when the file does not yet exist.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws \RuntimeException if flock() fails to acquire the shared lock.
     */
    public function findAll(): array
    {
        // If the file does not exist there are no products yet.
        if (!file_exists($this->storagePath)) {
            return [];
        }

        $handle = @fopen($this->storagePath, 'r');
        if ($handle === false) {
            return [];
        }

        // Acquire a shared (read) lock — blocks only if a writer holds LOCK_EX.
        if (!flock($handle, LOCK_SH)) {
            fclose($handle);
            throw new \RuntimeException('Could not acquire read lock on products.json');
        }

        $contents = stream_get_contents($handle);

        // Release the shared lock and close the handle.
        flock($handle, LOCK_UN);
        fclose($handle);

        // Decode JSON; fall back to empty array on null/invalid JSON.
        return json_decode($contents, true) ?? [];
    }

    /**
     * Find a single product by its UUID.
     *
     * Delegates to findAll() and filters in memory.
     *
     * @param string $id  UUID v4 of the product to find.
     * @return array<string, mixed>|null  The product array, or null if not found.
     *
     * @throws \RuntimeException if the underlying file read fails.
     */
    public function findById(string $id): ?array
    {
        $products = $this->findAll();

        foreach ($products as $product) {
            if (isset($product['id']) && $product['id'] === $id) {
                return $product;
            }
        }

        return null;
    }

    /**
     * Persist the full product collection to the JSON file.
     *
     * Uses 'c' mode to open (or create) the file WITHOUT truncating it first.
     * Truncation is deferred until after the exclusive lock is acquired, which
     * eliminates the race window that 'w' mode would introduce.
     *
     * @param array<int, array<string, mixed>> $products  The complete product list to persist.
     *
     * @throws \RuntimeException if the file cannot be opened, the exclusive lock
     *                           cannot be acquired, or the write fails.
     */
    public function save(array $products): void
    {
        // 'c' mode: open for writing; create the file if it does not exist;
        // do NOT truncate — truncation happens below, after the lock is held.
        $handle = fopen($this->storagePath, 'c');
        if ($handle === false) {
            throw new \RuntimeException('Could not open products.json for writing');
        }

        // Acquire an exclusive (write) lock — blocks until all readers and
        // other writers have released their locks.
        if (!flock($handle, LOCK_EX)) {
            fclose($handle);
            throw new \RuntimeException('Could not acquire write lock on products.json');
        }

        // Now that we hold the exclusive lock it is safe to truncate.
        // ftruncate() + rewind() ensures we start writing from byte 0 and
        // any previous content longer than the new content is removed.
        ftruncate($handle, 0);
        rewind($handle);

        $json = json_encode($products, JSON_PRETTY_PRINT);
        $written = fwrite($handle, $json);

        if ($written === false) {
            flock($handle, LOCK_UN);
            fclose($handle);
            throw new \RuntimeException('Failed to write data to products.json');
        }

        // Release the exclusive lock and close the handle.
        flock($handle, LOCK_UN);
        fclose($handle);
    }
}
