{{-- Modal Edit --}}
<div class="modal fade" id="editSiswa_{{ $data['id'] }}" tabindex="-1" aria-labelledby="editSiswaLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSiswaLabel">Edit Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('item.update', $data['id']) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="image" class="form-label text-start d-block">Gambar Buku</label>
                        <input type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror"
                            id="image" name="image">
                        @if ($data['image'])
                            <p>Current Image:</p>
                            <img src="{{ $data['image'] }}" alt="Current Image" width="150">
                        @endif
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label text-start d-block">Nama Item</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $data['name']) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="items_code" class="form-label text-start d-block">Kode Item</label>
                        <input type="text" class="form-control @error('items_code') is-invalid @enderror"
                            id="items_code" name="items_code" value="{{ old('items_code', $data['items_code']) }}">
                        @error('items_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label text-start d-block">Stok</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock"
                            name="stock" value="{{ old('stock', $data['stock']) }}">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label text-start d-block">Harga</label>
                        <input type="text" class="form-control @error('price') is-invalid @enderror" id="price"
                            name="price" value="{{ old('price', $data['price']) }}">
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Item</button>
                </form>
            </div>
        </div>
    </div>
</div>
