<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>PDF Dokumente Anzeigen</title>
    <style>
        .popup {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgba(0, 0, 0, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
        }
        .popup-content {
            background: white;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.5);
            overflow-y: auto;
            max-height: 80vh;
            width: 80%;
        }
        iframe {
            width: 100%;
            height: 500px;
        }
    </style>
</head>
<body>
    <a href="#" onclick="openPopup(); return false;">PDF Dokumente Anzeigen</a>

    <div class="popup" id="pdfPopup">
        <div class="popup-content" id="popupContent">
            <!-- Inhalte werden durch JavaScript hinzugefügt -->
        </div>
    </div>

    <script>
       function loadPDFs() {
    fetch('load_pdfs.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Netzwerkantwort war nicht ok');
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById('popupContent');
            data.forEach(pdf => {
                const iframe = document.createElement('iframe');
                iframe.src = 'dokumente/unpacked/' + pdf;
                container.appendChild(iframe);
            });
        })
        .catch(error => console.error('Fehler beim Laden der PDFs:', error));
}

    </script>
</body>
</html>
