<link rel="stylesheet" href="{{ asset('login_assets/css/style.css') }}" >
<div class="page">
  <form action="{{ route('AuthController.registerAction') }}" method="GET">
    <div class="container">
        
      <div class="left">
        <div class="login">Register</div>
        <div class="eula"> Here to add new user </div>
      </div>
      <div class="right">
          <path d="m 40,120.00016 239.99984,-3.2e-4 c 0,0 24.99263,0.79932 25.00016,35.00016 0.008,34.20084 -25.00016,35 -25.00016,35 h -239.99984 c 0,-0.0205 -25,4.01348 -25,38.5 0,34.48652 25,38.5 25,38.5 h 215 c 0,0 20,-0.99604 20,-25 0,-24.00396 -20,-25 -20,-25 h -190 c 0,0 -20,1.71033 -20,25 0,24.00396 20,25 20,25 h 168.57143" />
        </svg>
        <div class="form_register">
          <label for="email">User</label>
          <input id="email" name="username" style="border:solid 0.5px" value="{{ old('username') }}">
            <label style="margin-top: 2px">
              @error('username')
                  <span style="color: red">{{ $message }}</span>
              @enderror
              @if(session('fail'))
                  <div style="color: red">{{ session('fail') }}</div>
              @endif
            </label>
          <label for="password">Password</label>
          <input type="password" id="password" name="password" style="border:solid 1px">
            <label style="margin-top: 2px">
              @error('password')
                  <span style="color: red">{{ $message }}</span>
              @enderror
            </label>
          <label for="password">Confirm Password</label>
          <input type="password" id="password" name="confirm_password" style="border:solid 1px">
            <label style="margin-top: 2px">
              @error('confirm_password')
                  <span style="color: red">{{ $message }}</span>
              @enderror
            </label>
          <input type="submit" id="submit" style="margin-top: -4px" value="Submit">
        </div>
      </div>
    </div>
  </form>
</div>