<!doctype html>
<html lang="en">

<head>
    <title>Form Validation</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #name-error,
        #email-error,
        #phone-error,
        #image-error {
            color: red;
            display: none;
        }
    </style>
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main class="container-fluid">
        <div class="container m-3 p-3 mx-auto">

            <form class="d-flex justify-content-center flex-column" id="contact-form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="@error('name') is-invalid @enderror form-control" name="name" id="name" aria-describedby="helpId"
                        placeholder="Name">
                    <div id="name-error"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="@error('email') is-invalid @enderror form-control" name="email" id="email"
                        aria-describedby="emailHelpId" placeholder="abc@mail.com">
                    <div class="error-email" id="email-error"></div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Mobile Number</label>
                    <input type="text" class="@error('phone') is-invalid @enderror form-control" name="phone" id="phone" aria-describedby="helpId"
                        placeholder="Mobile Number">
                    <div class="error-phone" id="phone-error"></div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Image File</label>
                    <input type="file" class="@error('image') is-invalid @enderror form-control" name="image" id="image" aria-describedby="helpId"
                        placeholder="Image File">
                    <div class="error-image" id="image-error"></div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

        </div>

    </main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $("#contact-form").submit(function(event) {
                event.preventDefault();
                $("#name-error").hide();
                $("#email-error").hide();
                $("#phone-error").hide();
                $("#image-error").hide();

                const formData = new FormData();
                formData.append("name", $("#name").val());
                formData.append("email", $("#email").val());
                formData.append("phone", $("#phone").val());
                formData.append("image", $("#image").prop('files')[0]);
                formData.append("_token", "{{ csrf_token() }}");
                console.log(formData);

                $.ajax({
                    url: "{{ route('store') }}",
                    type: "post",
                    data: formData,
                    dataType: "json",
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // console.log(response);
                        if (response.errors) {
                            for (var key in response.errors) {
                                $("#" + key + "-error").show().text(response.errors[key]);
                                // console.log(key);
                                console.log(response.errors[key]);
                            }
                            return;
                        }
                        if (response.success) {
                            $("#contact-form")[0].reset();
                            $("#name-error").hide();
                            $("#email-error").hide();
                            $("#phone-error").hide();
                            $("#image-error").hide();
                            Swal.fire({
                                title: 'Success!',
                                text: response.success,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.error,
                                footer: '<a href="">Why do I have this issue?</a>'
                            });
                        }
                        $(this).submit();
                    }
                })
            })
        })
    </script>
</body>

</html>
