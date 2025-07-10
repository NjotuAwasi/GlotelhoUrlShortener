<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Url Shortener</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">


</head>

<body>
    <!-- As a heading -->
<nav class="navbar navbar-light bg-light justify-content-center">
  <span class="navbar-brand mb-0 h1">URL Shortener Glotelho</span>
</nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h1><marquee behavior="" direction=""> Use the URL shortener</h1></marquee>
                    </div>
                    <div class="card-body">
                        <form id="shorten">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Enter url you wish to shorten</label>
                                <input type="url" class="form-control" id="original_url" aria-describedby="emailHelp" placeholder="https://example.com">
                                <small id="urlHelp" class="form-text text-muted">Please make sure to enter a valid link</small>
                            </div>

                            <button type="submit" class="btn btn-primary">Shorten</button>
                        </form>
                        <div class="mt-3" id="result" style="display:none;">
                            <h5>Shortened Url:</h5>
                            <a href="#" target="_blank" id="short_url" class="btn btn-link"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>


    <script>

        //we add a listener for when the submit button is clicked
        document.getElementById('shorten').addEventListener('submit', function(event) {
            event.preventDefault();

            //we get the value from the input the user filled in the form
            const originalUrl = document.getElementById('original_url').value;

            //we pass the value to the post route we created in web.php
            //we must add csrf token as it is required by laravel
            fetch('{{ route("url.short") }}' , {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({original_url: originalUrl})
                //we receive the response to json and get the short url from the controller
            }).then(response => response.json()).then(data=>{
                const shortUrlElement = document.getElementById('short_url');
                shortUrlElement.href = data.short_url;
                shortUrlElement.textContent = data.short_url;

                //display the link once the url is shortened
                document.getElementById('result').style.display = 'block';
            }).catch(error=>console.error('Error: ', error))

        });
    </script>
</body>

</html>
