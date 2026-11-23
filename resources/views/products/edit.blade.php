@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('products.show', $product) }}" class="btn btn-light me-3" title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 fw-bold text-sari-dark mb-1">Edit Product</h1>
                <p class="text-muted mb-0">Update the details for <strong>{{ $product->name }}</strong>.</p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square"></i> Product Information</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('products.update', $product) }}" novalidate>
                    @csrf
                    @method('PUT')
                    @include('products._form', ['button' => 'Update Product'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
