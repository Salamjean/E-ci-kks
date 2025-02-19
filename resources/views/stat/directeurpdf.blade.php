<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            position: relative;
            margin: 0;
            height: 100vh;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0.3;
            z-index: -1;
        }
        .logo1 {
            position: absolute;
            margin-left: 75%;
            margin-top: 2;
            height: 110px;
            left: 40px;
        }
        .logo2 {
            height: 130px;
            position: absolute;
            left: -50px;
            margin-left: 0;
            margin-top: 0;
        }
        .logo {
            display: flex;
            justify-content: space-between;
        }
        .tete {
            text-align: center;
            font-family: calisto MT;
            font-size: 25px;
            color: #006;
            font-weight: bold;
        }
        .signature {
            position: absolute;
            bottom: 300px;
            right: 25px;
            font-size: 15px;
            font-weight: bold;
        }
        hr {
            position: absolute;
            bottom: 85px;
            width: 90%;
            border: 1px solid black;
            margin-left: 20px;
        }
        footer {
            position: absolute;
            bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo_g">
            <div class="logo">
                <img src="assets/images/profiles/sante.jpg" class="logo2" alt="Logo">
                <img src="assets/images/profiles/gouv_ci.png" class="logo1" alt="Logo">
            </div>
        </div>
        <br><br><br><br><br><br><br><br>
        <h1 class="tete">{{ $communeAdmin }}</h1>
    </header>
    <main>
        <h1 style="text-align: center">Statistiques</h1>
        <table>
            <thead>
                <tr style="text-align: center">
                    <th>Déclaration</th>
                    <th>Nombre</th>
                </tr>
            </thead>
            <tbody>
                <tr style="text-align: center">
                    <td>Naissances</td>
                    <td>{{ $naisshopCount }}</td>
                </tr>
                <tr style="text-align: center">
                    <td>Décès</td>
                    <td>{{ $deceshopCount }}</td>
                </tr>
            </tbody>
        </table>

        <h2 style="text-align: center">Les statisques par Mois</h2>
        <table>
            <thead>
                <tr style="text-align: center">
                    <th>Mois</th>
                    <th>Naissances</th>
                    <th>Décès</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 12; $i++)
                    <tr style="text-align: center">
                        <td>{{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</td>
                        <td>{{ $naissData[$i] ?? 0 }}</td>
                        <td>{{ $decesData[$i] ?? 0 }}</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </main>
</body>
</html>