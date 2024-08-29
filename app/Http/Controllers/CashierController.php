<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;

class CashierController extends Controller
{
    public function index_admin()
    {
        return view('index_admin');
    }

    public function index_kasir()
    {
        return view('index_kasir');
    }

    
    // === User ===

    public function data_admin()
    {
        try {
            $response = Http::get('http://localhost:8001/api/users');
    
            if ($response) {
                $datas = json_decode($response->getBody()->getContents(), true);
                // dd($datas);
                return view('data_users', compact('datas'));
            } else {
                // Jika respons tidak sukses, kirim error 404
                return response()->view('errors.404', [], 404);
            }
        } catch (ConnectionException $e) {
            // Tangani error koneksi dengan menampilkan halaman error 404
            return response()->view('errors.404', [], 404);
        } catch (RequestException $e) {
            // Tangani error permintaan lainnya
            return response()->view('errors.500', [], 500); // Ganti dengan view yang sesuai jika perlu
        }
    }

    public function create_user()
    {
        return view('partials.users.create_user');
    }

    public function store_user(Request $request)
    {
        
    }



    public function delete_user($id)
    {
        $data = ['is_deleted' => 1];

        try {
            $response = Http::put("http://localhost:8001/api/users/update/{$id}", $data);

            if ($response->successful()) {
                return redirect()->route('index_user')->with('success', 'User deactivated successfully.');
            } else {
                return back()->with('error', 'Failed to deactivate user. Please try again.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to connect to the API. Please try again.');
        }
    }



    // === End User ===

    // === Kasir ===

    // public function create_transaksi()
    // {
    //     return view('partials.transaksi.create_transaksi');
    // }


    
    public function createInvoice()
    {
        // Return the view with the invoice number
        return view('partials.transaksi.create_transaksi');
    }



    public function index_transaksi()
    {
        try {
            $response = Http::get('http://localhost:8001/api/transaksi');

            if ($response) {
                $datas = json_decode($response->getBody()->getContents(), true);
                // dd($datas);
                return view('index_kasir', compact('datas'));
            } else {
                // Jika respons tidak sukses, kirim error 404
                return response()->view('errors.404', [], 404);
            }
        }   catch (ConnectionException $e) {
            // Tangani error koneksi dengan menampilkan halaman error 404
            return response()->view('errors.404', [], 404);
        } catch (RequestException $e) {
            // Tangani error permintaan lainnya
            return response()->view('errors.500', [], 500); // Ganti dengan view yang sesuai jika perlu
        }
    }

    

    // === End Kasir ===
    
    
    // === Item ===

    public function create_item()
    {
        return view('partials.items.create_item');
    }

    // public function search_item()
    // {
    //     // Retrieve the search query from the request
    //     $search = request()->query('search');

    //     // Make an API request to fetch the items
    //     $response = Http::get('http://localhost:8001/api/items', [
    //         'search' => $search
    //     ]);

    //     // Decode the JSON response into an array
    //     $items = $response->json();

    //     // Return the items as a JSON response for the AJAX request
    //     return response()->json($items);
    // }


    public function index_item()
    {
        try {
            $response = Http::get('http://localhost:8001/api/items');
    
            if ($response) {
                $datas = json_decode($response->getBody()->getContents(), true);
                // dd($datas);
                return view('data_items', compact('datas'));
            } else {
                // Jika respons tidak sukses, kirim error 404
                return response()->view('errors.404', [], 404);
            }
        }   catch (ConnectionException $e) {
            // Tangani error koneksi dengan menampilkan halaman error 404
            return response()->view('errors.404', [], 404);
        } catch (RequestException $e) {
            // Tangani error permintaan lainnya
            return response()->view('errors.500', [], 500); // Ganti dengan view yang sesuai jika perlu
        }
    }

    
    public function store_item(Request $request)
    {
        $request->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'items_code' => 'required|string|max:255',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            // Path gambar yang dapat diakses oleh browser
            $imagePath = url('/images/' . $name);

            // Mengirim data ke API
            $data = [
                'image' => $imagePath,
                'name' => $request->input('name'),
                'items_code' => $request->input('items_code'),
                'stock' => $request->input('stock'),
                'price' => $request->input('price'),
                'is_deleted' => 0,
            ];

            try {
                $response = Http::post('http://localhost:8001/api/items/store', $data);

                if ($response->successful()) {
                    return redirect()->route('index_item')->with('success', 'Item created successfully.');
                } else {
                    return back()->with('error', 'Failed to create item. Please try again.');
                }
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to connect to the API. Please try again.');
            }
        }

        return back()->with('error', 'Please upload an image.');
    }

    public function update_item(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'items_code' => 'required|string|max:255',
            'stock' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        // Ambil data item saat ini dari API untuk mendapatkan gambar lama jika tidak ada gambar baru yang diupload
        $currentItemResponse = Http::get("http://localhost:8001/api/items/update/{$id}");
        $currentItem = $currentItemResponse->json();

        // Buat array data dengan nilai-nilai baru atau nilai lama jika tidak diubah
        $data = [
            'name' => $request->input('name'),
            'items_code' => $request->input('items_code'),
            'stock' => $request->input('stock'),
            'price' => $request->input('price'),
            'is_deleted' => 0, // Keep is_deleted as 0 when updating
            'image' => isset($currentItem['image']) ? $currentItem['image'] : null, // Default to current image if exists
        ];

        // Jika ada gambar baru, ganti data gambar dengan yang baru
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            // Ubah path gambar jika ada gambar baru yang diupload
            $data['image'] = url('/images/' . $name);
        }

        try {
            $response = Http::put("http://localhost:8001/api/items/update/{$id}", $data);

            if ($response->successful()) {
                return redirect()->route('index_item')->with('success', 'Item updated successfully.');
            } else {
                return back()->with('error', 'Failed to update item. Please try again.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to connect to the API. Please try again.');
        }
    }


    public function delete_item($id)
    {
        // Prepare data for deletion
        $data = ['is_deleted' => 1];

        try {
            // Send a DELETE request
            $response = Http::delete("http://localhost:8001/api/items/delete/{$id}", $data);

            if ($response->successful()) {
                return redirect()->route('index_item')->with('success', 'Item deleted successfully.');
            } else {
                return back()->with('error', 'Failed to delete item. Please try again.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to connect to the API. Please try again.');
        }
    }


    // === End Item ===

    
}
