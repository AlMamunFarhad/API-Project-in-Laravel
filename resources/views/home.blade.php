<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center align-content-center align-items-center vh-100">
            <div class="col-md-4 card p-5">
                <div class="form-title mb-4">
                    <h1>Login</h1>
                </div>
                <div class="mb-4">
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email">
                </div>
                <div class="mb-4">
                    <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                </div>
                <button id="loginBtn" type="submit" class="btn btn-primary">Login</button>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>
        $(document).ready(function() {
            $("#loginBtn").on('click', function() {
                const email = $("#email").val();
                const password = $("#password").val();

                $.ajax({
                    url: '/api/login',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        email: email,
                        password: password
                    }),
                    success: function(response) {
                        console.log(response);
                        localStorage.setItem('api_token', response.token);
                        window.location.href = "http://localhost:8000/view/posts";
                    },
                    error: function(xhr, status, error) {
                        alert('Error:', xhr.reponseText);
                    }
                });
            });

        });
    </script>

</body>

</html>
