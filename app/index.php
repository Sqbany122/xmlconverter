<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="p-4">
            <form id="form" action="convert.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <input class="form-control" type="file" name="xmlfile" accept=".xml" required style="width: 350px;" />
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Konwertuj</button>
                </div>
            </form>

            <div id="loadingMessage" class="alert alert-info" style="display: none; width: 350px;">
                Trwa konwertowanie...
            </div>
            <div id="successMessage" class="alert alert-success" style="display: none; width: 350px;">
                Konwersja przebiegła pomyślnie.
            </div>
        </div>
        <script>
            let form = document.getElementById("form");
            let loadingMessage = document.getElementById("loadingMessage");
            let successMessage = document.getElementById("successMessage");

            form.addEventListener("submit", async function(e) {
                e.preventDefault();

                const formData = new FormData();
                const fileInput = document.querySelector('input[type="file"]');
                formData.append('xmlfile', fileInput.files[0]);

                loadingMessage.style.display = 'block';

                try {
                    const response = await fetch('convert.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        loadingMessage.style.display = 'none';
                        successMessage.style.display = 'block';

                        setTimeout(() => {
                            successMessage.style.display = 'none';
                        }, 10000);
                        download('/uploads/xml_upload.xml', 'xml_upload.xml')
                    } else {
                        throw new Error('Conversion failed');
                    }
                } catch (error) {
                    showErrorMessage();
                    console.error('Error:', error);
                }
            });

            function download(dataurl, filename) {
                var a = document.createElement("a");
                a.href = dataurl;
                a.setAttribute("download", filename);
                a.click();
                return false;
            }

        </script>
    </body>
</html>