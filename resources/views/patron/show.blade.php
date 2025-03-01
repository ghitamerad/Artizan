<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patron généré</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/panzoom/9.4.1/panzoom.min.js"></script>
    <style>
        body { text-align: center; font-family: Arial, sans-serif; }
        #patron-container { width: 80%; margin: auto; border: 1px solid #ddd; overflow: hidden; }
        object { width: 100%; height: 90vh; }
    </style>
</head>
<body>
    <h1>Patron du modèle</h1>
    <div id="patron-container">
        <object id="patron-svg" type="image/svg+xml" data="{{ $svgPath }}"></object>
    </div>

    <script>
        const element = document.querySelector("#patron-svg");
        panzoom(element); // Permet de zoomer/déplacer le patron SVG
    </script>
</body>
</html>
