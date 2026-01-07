<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h2 class="fw-bold mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">
                    {{ __('Profil Saya') }}
                </h2>
                <p class="text-muted small mb-0">Kelola informasi akun dan keamanan Anda di sini.</p>
            </div>
            <i class="bi bi-person-bounding-box fs-1" style="color: #800000; opacity: 0.15;"></i>
        </div>
    </x-slot>

    <div class="py-5" style="background-color: #f8f9fa;"> 
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body p-4 p-lg-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="rounded-circle p-3 me-3" style="background-color: rgba(128, 0, 0, 0.1); color: #800000;">
                                    <i class="bi bi-person-vcard fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">Informasi Pribadi</h5>
                                    <p class="text-muted small mb-0">Perbarui nama, email, kontak, dan alamat pengiriman.</p>
                                </div>
                            </div>
                            <hr class="border-light mb-4">
                            <div class="profile-form-wrapper">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle p-2 me-3" style="background-color: rgba(128, 0, 0, 0.1); color: #800000;">
                                    <i class="bi bi-shield-lock fs-5"></i>
                                </div>
                                <h6 class="fw-bold mb-0">Ubah Kata Sandi</h6>
                            </div>
                            <div class="small text-muted mb-3">
                                Pastikan akunmu tetap aman dengan password yang kuat.
                            </div>
                            <div class="profile-compact-form">
                                @include('profile.partials.update-password-form')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .profile-form-wrapper input, 
    .profile-form-wrapper textarea,
    .profile-compact-form input {
        width: 100%;
        padding: 0.6rem 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        margin-bottom: 1rem;
        transition: border-color 0.3s ease-in-out;
    }

    .profile-form-wrapper input:focus,
    .profile-form-wrapper textarea:focus,
    .profile-compact-form input:focus {
        border-color: #800000;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(128, 0, 0, 0.1);
    }
    
    .profile-form-wrapper label,
    .profile-compact-form label {
        font-weight: 600;
        font-size: 0.85rem;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    button[type="submit"] {
        background-color: #800000;
        color: white;
        padding: 0.6rem 2rem;
        border-radius: 50px;
        border: none;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #a05d69;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(128, 0, 0, 0.2);
    }
</style>