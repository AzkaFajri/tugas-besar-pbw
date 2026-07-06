<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Raynor System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        
        .login-container h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
        }
        
        .role-info {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.6;
            color: #555;
        }
        
        .role-info strong {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        
        .role {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Raynor System</h1>
        
        <form method="POST" action="{{ route('auth.login') }}">
            @csrf
            
            <div class="form-group">
                <label for="nama_pegawai">Nama Pegawai</label>
                <input 
                    type="text" 
                    id="nama_pegawai" 
                    name="nama_pegawai" 
                    value="{{ old('nama_pegawai') }}" 
                    required
                    placeholder="Masukkan nama pegawai"
                >
                @error('nama_pegawai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="hp_pegawai">Nomor HP</label>
                <input 
                    type="text" 
                    id="hp_pegawai" 
                    name="hp_pegawai" 
                    required
                    placeholder="Masukkan nomor HP pegawai"
                >
                @error('hp_pegawai')
                    <div class="error-message">{{ $message }}</div>
                @enderror
                @error('login')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="btn-login">Login</button>
        </form>
        
        <div class="role-info">
            <strong>Demo Credentials (Pegawai):</strong>
            <div class="role">
                <strong>Owner:</strong><br>
                Nama: Hanif Fathi<br>
                HP: 081211223344
            </div>
            <div class="role">
                <strong>Admin:</strong><br>
                Nama: Kevin Chandra<br>
                HP: 082673629765
            </div>
        </div>
    </div>
</body>
</html>
