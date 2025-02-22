@extends('structure.main')

@section('content')
    <div class="container">
        <h2>Create Coupon</h2>
        <form action="{{ route('coupons.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Coupon Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Discount Percentage:</label>
                <input type="number" name="discount" class="form-control" min="1" max="100" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Duration:</label>
                <select name="duration" class="form-control" required>
                    <option value="forever">Forever</option>
                    <option value="once">Once</option>
                    <option value="repeating">Repeating</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create Coupon</button>
        </form>
    </div>
    <div class="container mt-5">
        

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover table-striped" id="couponsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Discount (%)</th>
                    <th>Duration</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($coupons as $coupon)
                    <tr>
                        <td>{{ $coupon->name }}</td>
                        <td>{{ $coupon->discount }}%</td>
                        <td>{{ $coupon->duration }}</td>
                        <td>
                           
                            <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        #couponsTable{
            border: 2px solid black;
        }
    </style>
@endsection
