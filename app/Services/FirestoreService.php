<?php

namespace App\Services;

use Google\Cloud\Core\Exception\GoogleException;
use Google\Cloud\Firestore\FirestoreClient;

class FirestoreService
{
    protected FirestoreClient $firestoreClient;

    /**
     * @throws GoogleException
     * @throws \Exception
     */
    public function __construct($keyFilePath)
    {
        $keyData = json_decode(file_get_contents($keyFilePath), true);
        $projectId = $keyData['project_id'] ?? '';

        if (empty($projectId)) {
            throw new \Exception("Cannot determine project_id from the key file.");
        }

        $this->firestoreClient = new FirestoreClient([
            "keyFilePath" => $keyFilePath,
            "projectId" => $projectId,
        ]);
    }


    public function getAllData($collectionName): array
    {
        $documents = $this->firestoreClient->collection($collectionName)->documents();
        $allData = [];

        foreach ($documents as $document) {
            if ($document->exists()) {
                $allData[] = $document->data();
            }
        }

        return $allData;
    }
}
