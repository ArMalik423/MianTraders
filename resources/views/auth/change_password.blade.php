@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                        <div class="card-header">Update Password</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('post.change.password') }}"  id="login-form-id">
                            @csrf

                            <div class="row mb-3">
                                <label for="old_password" class="col-md-4 col-form-label text-md-end">Old Password</label>

                                <div class="col-md-6">
                                    <input id="old_password" name="old_password" class="form-control" type="password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">New Password</label>

                                <div class="col-md-6">
                                    <input id="password" name="password" class="form-control" type="password">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">Password Confirmation</label>

                                <div class="col-md-6">
                                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control">

                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button onclick="validateFieldsByFormId(this)"  data-validation="validation-span-id"
                                            id="validation-span-id" class="btn btn-primary">
                                        Update Password
                                    </button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
