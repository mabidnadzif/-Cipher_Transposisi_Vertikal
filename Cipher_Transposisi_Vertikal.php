<!DOCTYPE html>
<html>
<head>
    <title>Cipher Transposisi Vertikal</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-primary:hover {
            background-color: #117a8b;
            border-color: #117a8b;
        }

        h1 {
            color: #333;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.2/FileSaver.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <nav class="navbar navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand font-weight-bold" href="#">
                    <img src="./kumis.png" alt="" width="30" height="30" class="d-inline-block align-text-top" class="">
                    Cipher Transposisi Vertikal
                </a>
            </div>
        </nav>
        <br>
        <div class="row">
            <div class="col-md-6">
                <h2>Enkripsi</h2>
                <textarea id="encryptInput" rows="4" class="form-control mb-2" placeholder="Masukkan teks plainteks"></textarea>
                <input type="password" id="encryptPassword" class="form-control mb-2" placeholder="Password Enkripsi">
                <input type="file" id="fileInputEncrypt" class="form-control-file mb-2" accept=".txt">
                <button onclick="encryptText()" class="btn btn-primary">Enkripsi Teks</button>
                <button onclick="encryptFile()" class="btn btn-warning">Enkripsi File</button>
                <a id="downloadEncryptLink" style="display: none">Download Hasil Enkripsi</a>
                <h3 class="mt-3">Hasil Enkripsi:</h3>
                <textarea id="encryptOutput" rows="4" class="form-control" readonly></textarea>
            </div>
            <div class="col-md-6">
                <h2>Deskripsi</h2>
                <textarea id="decryptInput" rows="4" class="form-control mb-2" placeholder="Masukkan teks chiperteks"></textarea>
                <input type="password" id="decryptPassword" class="form-control mb-2" placeholder="Password Deskripsi">
                <input type="file" id="fileInputDecrypt" class="form-control-file mb-2" accept=".txt">
                <button onclick="decryptText()" class="btn btn-primary">Deskripsi Teks</button>
                <button onclick="decryptFile()" class="btn btn-warning">Deskripsi File</button>
                <a id="downloadDecryptLink" style="display: none">Download Hasil Deskripsi</a>
                <h3 class="mt-3">Hasil Deskripsi:</h3>
                <textarea id="decryptOutput" rows="4" class="form-control" readonly></textarea>
            </div>
        </div>
    </div>
    <script>
        let encryptionPassword = '';
        const EMPTY_CHAR_REPLACEMENT = '_';

        function transposeEncrypt(text, password) {
            encryptionPassword = password;
            const key = password.split('').map(c => c.charCodeAt(0));
            const rows = key.length;
            const cols = Math.ceil(text.length / rows);
            const matrix = new Array(rows).fill(0).map(() => new Array(cols).fill(''));
            let index = 0;
            for (let j = 0; j < cols; j++) {
                for (let i = 0; i < rows; i++) {
                    if (index < text.length) {
                        matrix[i][j] = text[index];
                        index++;
                    }
                }
            }
            let result = '';
            for (let i = 0; i < rows; i++) {
                for (let j = 0; j < cols; j++) {
                    result += matrix[i][j] || EMPTY_CHAR_REPLACEMENT;
                }
            }
            return result;
        }

        function transposeDecrypt(text, password) {
            if (password !== encryptionPassword) {
                return 'Password Deskripsi tidak cocok.';
            }
            const key = password.split('').map(c => c.charCodeAt(0));
            const rows = key.length;
            const cols = Math.ceil(text.length / rows);
            const matrix = new Array(rows).fill(0).map(() => new Array(cols).fill(''));
            let index = 0;
            for (let i = 0; i < rows; i++) {
                for (let j = 0; j < cols; j++) {
                    matrix[i][j] = text[index];
                    index++;
                }
            }
            let result = '';
            for (let j = 0; j < cols; j++) {
                for (let i = 0; i < rows; i++) {
                    if (matrix[i][j] !== EMPTY_CHAR_REPLACEMENT) {
                        result += matrix[i][j];
                    }
                }
            }
            return result;
        }

        function validatePassword(password) {
            if (password.length < 4) {
                return false;
            }
            return true;
        }

        function encryptText() {
            const input = document.getElementById('encryptInput').value;
            const password = document.getElementById('encryptPassword').value;
            if (!validatePassword(password)) {
                alert('Password Enkripsi tidak valid.');
                return;
            }
            const output = document.getElementById('encryptOutput');
            const encryptedText = transposeEncrypt(input, password);
            output.value = encryptedText;
            const downloadLink = document.getElementById('downloadEncryptLink');
            downloadLink.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(encryptedText);
            downloadLink.download = 'encrypted.txt';
            downloadLink.style.display = 'block';
        }

        function decryptText() {
            const input = document.getElementById('decryptInput').value;
            const password = document.getElementById('decryptPassword').value;
            if (!validatePassword(password)) {
                alert('Password Deskripsi tidak valid.');
                return;
            }
            const output = document.getElementById('decryptOutput');
            const decryptedText = transposeDecrypt(input, password);
            output.value = decryptedText;
            const downloadLink = document.getElementById('downloadDecryptLink');
            downloadLink.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(decryptedText);
            downloadLink.download = 'decrypted.txt';
            downloadLink.style.display = 'block';
        }

        function encryptFile() {
            const password = document.getElementById('encryptPassword').value;
            const fileInput = document.getElementById('fileInputEncrypt');
            if (!validatePassword(password)) {
                alert('Password Enkripsi tidak valid.');
                return;
            }
            if (!fileInput.files.length) {
                alert('Pilih berkas untuk dienkripsi.');
                return;
            }
            const file = fileInput.files[0];
            const reader = new FileReader();
            reader.onload = function(event) {
                const text = event.target.result;
                const encryptedText = transposeEncrypt(text, password);
                const output = document.getElementById('encryptOutput');
                output.value = encryptedText;
                const downloadLink = document.getElementById('downloadEncryptLink');
                downloadLink.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(encryptedText);
                downloadLink.download = 'encrypted.txt';
                downloadLink.style.display = 'block';
            };
            reader.readAsText(file);
        }

        function decryptFile() {
            const password = document.getElementById('decryptPassword').value;
            const fileInput = document.getElementById('fileInputDecrypt');
            if (!validatePassword(password)) {
                alert('Password Deskripsi tidak valid.');
                return;
            }
            if (!fileInput.files.length) {
                alert('Pilih berkas untuk didekripsi.');
                return;
            }
            const file = fileInput.files[0];
            const reader = new FileReader();
            reader.onload = function(event) {
                const text = event.target.result;
                const decryptedText = transposeDecrypt(text, password);
                const output = document.getElementById('decryptOutput');
                output.value = decryptedText;
                const downloadLink = document.getElementById('downloadDecryptLink');
                downloadLink.href = 'data:text/plain;charset=utf-8,' + encodeURIComponent(decryptedText);
                downloadLink.download = 'decrypted.txt';
                downloadLink.style.display = 'block';
            };
            reader.readAsText(file);
        }
    </script>
</body>
</html>
