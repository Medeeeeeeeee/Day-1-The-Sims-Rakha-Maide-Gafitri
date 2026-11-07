@extends('layouts.app')

@section('content')
<style>
    .assistant-form-page { min-height: 100vh; background: #f2f2f2; padding: 0; font-family: Arial, Helvetica, sans-serif; }
    .assistant-form-shell { width: 100%; min-height: 100vh; background: #ffffff; }
    .assistant-form-header { background: #2d7be8; color: #ffffff; text-align: center; font-size: 33px; font-weight: 500; line-height: 1.2; padding: 10px 20px 12px; }
    .assistant-form-body { padding: 14px 20px 24px; }
    .assistant-errors { margin-bottom: 16px; border-radius: 12px; border: 1px solid #fecaca; background: #fef2f2; color: #991b1b; padding: 12px 14px; font-size: 14px; font-weight: 600; }
    .assistant-grid { display: grid; grid-template-columns: 1.15fr 1fr; gap: 34px 48px; align-items: start; }
    .assistant-field { margin-bottom: 10px; }
    .assistant-label { display: block; color: #111111; font-size: 27px; font-weight: 500; line-height: 1.2; margin-bottom: 8px; }
    .assistant-input, .assistant-select { width: 100%; height: 76px; border: 1.8px solid #d0d0d0; border-radius: 16px; background: #ffffff; box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16); color: #111111; font-size: 25px; font-family: Arial, Helvetica, sans-serif; outline: none; padding: 0 18px; }
    .assistant-input::placeholder { color: #c6c6c6; }
    .assistant-hint { margin-top: 8px; color: #666666; font-size: 13px; }
    .assistant-bottom { display: flex; align-items: flex-end; gap: 26px; margin-top: 6px; }
    .assistant-submit { display: inline-flex; align-items: center; justify-content: center; min-width: 224px; height: 50px; border: 0; border-radius: 16px; background: #12c100; color: #ffffff; font-size: 26px; font-weight: 500; cursor: pointer; }
    .assistant-cancel { display: inline-flex; align-items: center; justify-content: center; min-width: 180px; height: 50px; border-radius: 16px; border: 1.8px solid #d0d0d0; background: #ffffff; color: #555555; font-size: 22px; font-weight: 500; text-decoration: none; box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08); }
</style>

<div class="assistant-form-page">
    <div class="assistant-form-shell">
        <div class="assistant-form-header">Form Edit Asisten</div>
        <div class="assistant-form-body">
            @if($errors->any())
            <div class="assistant-errors">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
            @endif
            <form method="POST" action="{{ route('admin.assistants.update', $assistant) }}">
                @csrf
                @method('PUT')
                <div class="assistant-grid">
                    <div>
                        <div class="assistant-field">
                            <label class="assistant-label" for="nim">NIM / Nama Asisten</label>
                            <input id="nim" class="assistant-input" type="text" name="nim" value="{{ old('nim', $assistant->nim) }}" required>
                        </div>
                        <div class="assistant-field">
                            <label class="assistant-label" for="nama">Nama</label>
                            <input id="nama" class="assistant-input" type="text" name="nama" value="{{ old('nama', $assistant->nama) }}" required>
                        </div>
                        <div class="assistant-field">
                            <label class="assistant-label" for="email">Email</label>
                            <input id="email" class="assistant-input" type="email" name="email" value="{{ old('email', $assistant->email) }}" required>
                        </div>
                    </div>
                    <div>
                        <div class="assistant-field">
                            <label class="assistant-label" for="gender">Gender</label>
                            <select id="gender" class="assistant-select" name="gender">
                                <option value="">Pilih Gender</option>
                                <option value="Laki-Laki" {{ old('gender', $assistant->gender) === 'Laki-Laki' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="Perempuan" {{ old('gender', $assistant->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="assistant-field">
                            <label class="assistant-label" for="password">Password Baru</label>
                            <input id="password" class="assistant-input" type="password" name="password" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="assistant-field">
                            <label class="assistant-label" for="password_confirmation">Konfirmasi Password</label>
                            <input id="password_confirmation" class="assistant-input" type="password" name="password_confirmation" placeholder="Ulangi password baru">
                            <div class="assistant-hint">Jika password tidak ingin diganti, biarkan kosong.</div>
                        </div>
                    </div>
                </div>
                <div class="assistant-bottom">
                    <a href="{{ route('admin.assistants.index') }}" class="assistant-cancel">Batal</a>
                    <button type="submit" class="assistant-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
