<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Shortener - IUMI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="public/style.css">
</head>

<body>
    <main>
        <nav class="navbar navbar-light bg-light mb-5">
            <div class="container">
                <a class="navbar-brand">IUMI</a>
            </div>
        </nav>
        <div class="container">
            <form id="linkForm">
                <h1 class="title text-center">
                    Link Shortener
                </h1>
                <div class="mb-3">
                    <input type="text" id="linkInput" class="link_input" aria-describedby="link" placeholder="Enter your long link">
                </div>
                <div class="d-flex justify-content-center" id="link_btn">
                    <button type="submit" class="link_submit" id="linkSumit">Submit</button>
                </div>
            </form>
            <hr>
            <div id="link_err" style="display: none;">
                <p class="text-danger fs-5">Shorten your link is failed.</p>
            </div>
            <div id="link_output" style="display: none;">
                <p class="fs-5" id="slink">adf</p>
                <hr>
                <button class="btn btn-outline-primary" onclick="copyToClipboard(this)">Copy</button>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#linkForm").submit(function(e) {
                e.preventDefault()

                getShorLink($("#linkInput").val())

            })
        })


        function getShorLink(link) {
            $("#linkSumit").html("Shortening...").attr("disabled", true);
            $.ajax({
                url: "api/get_short_link",
                type: "POST",
                data: {
                    link
                }

            }).done(function(data) {
                $("#link_output").show();
                hostname=window.location.origin;
                $("#slink").text(hostname+"/"+data);
            }).fail(function() {
                $("#link_err").show();
            }).always(function() {
                $("#linkSumit").html("Submit").attr("disabled", false);
            })
        }




        const unsecuredCopyToClipboard = (text) => {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy')
            } catch (err) {
                console.error('Unable to copy to clipboard', err)
            }
            document.body.removeChild(textArea)
        };

        function copyToClipboard(element) {
            var temp = $("#slink").html();

            if (window.isSecureContext && navigator.clipboard) {
                navigator.clipboard.writeText(temp);
            } else {
                unsecuredCopyToClipboard(temp);
            }

            $(element).html("Copied");
        }
    </script>
</body>

</html>