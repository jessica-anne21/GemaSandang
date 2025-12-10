@extends('layouts.main')
@section('content')

<div class="container my-5">
    <h1 class="display-5 mb-4" style="font-family: 'Playfair Display', serif;">Keranjang Belanja Anda</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(!empty($cart))
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Produk</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Kuantitas</th>
                    <th scope="col" class="text-end">Subtotal</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $id => $details)
                    @php $total += $details['price'] * $details['quantity']; @endphp
                    <tr>
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-sm-3 hidden-xs">
                                    <img src="{{ asset('storage/' . $details['photo']) }}" width="100" class="img-responsive"/>
                                </div>
                                <div class="col-sm-9">
                                    <h4 class="nomargin">{{ $details['name'] }}</h4>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                        <td data-th="Quantity">
                            <input type="number" value="{{ $details['quantity'] }}" class="form-control form-control-sm text-center" readonly />
                        </td>
                        <td data-th="Subtotal" class="text-end">
                            Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}
                        </td>
                        <td class="actions">
                            <form action="{{ route('cart.remove', $id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus produk ini dari keranjang?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-end"><h3><strong>Total Rp {{ number_format($total, 0, ',', '.') }}</strong></h3></td>
                </tr>
                <tr>
                    <td colspan="5" class="text-end">
                        <a href="{{ route('shop') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Lanjutkan Belanja</a>
                        <a href="{{ route('checkout.index') }}" class="btn btn-custom">
                            Checkout <i class="bi bi-arrow-right"></i>
                        </a>                   
                    </td>
                </tr>
            </tfoot>
        </table>
    @else
        <div class="alert alert-info text-center">
            <p>Keranjang belanja Anda masih kosong.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Mulai Belanja</a>
        </div>
    @endif 
</div>
@endsection