function login() {
    // Mendapatkan nilai dari elemen dengan ID yang sesuai
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    var role = document.getElementById('role').value; 
    var rememberMe = document.getElementById('rememberMe').checked;

    // Memeriksa jika username atau password kosong
    if (username === "" || password === "") {
        alert("Please fill in all fields.");
        return;
    }

    // Simulasi proses login
    alert("Logging in as " + role + "\nUsername: " + username + "\nRemember me: " + rememberMe);

    // Menampilkan halaman kosong dengan pesan "Login Success"
    document.body.innerHTML = '<div class="success-message">Login Success</div>';
} 