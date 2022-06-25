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

    <ul id="list"></ul>

    <script>
        const sortFunction = (a, b) => a.label.toLowerCase() > b.label.toLowerCase() ? 1 : -1
        const renderList = async () => {
            let view = document.querySelector('#list');
            let data = await fetch('/fetch').then(result => result.json()).then(json => json)

            let element = ''
            data.sort(sortFunction).map(menu => {
                return element += `<li>
                <p onclick="PopUp('${menu.label}','${menu.id}')">${menu.label}</p>
<ul>
                    ${menu.children.sort(sortFunction).map(child => renderSubList(child)).join('')}
                </ul>
            </li>`
            }).join('')

            view.innerHTML = element

        }
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


        const renderSubList = (menu) => {
            return `<li>
                <p onclick="PopUp('${menu.label}','${menu.id}')">${menu.label}</p>
                ${!menu.children?.length ? '' : `
                <ul>
                    ${menu.children.sort(sortFunction).map(child => renderSubList(child)).join('')}
                </ul>
                `}
            </li>`
        }

        renderList()
    </script>

</body>

</html>