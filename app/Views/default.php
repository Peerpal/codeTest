<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico" />
</head>

<body>
    <?= $this->renderSection('content') ?>

    <script>
        const PopUp = async (label, id) => {
            let updateValue = prompt(`update menu label ${label}`)
            try {
                if (updateValue !== "") {
                    fetch(`/edit/${id}/${updateValue}`).then(r => window.location.reload())
                } else {
                    alert("Invalid data");
                }
            } catch (error) {
                alert(error.message)
            }
        }
    </script>

</body>

</html>