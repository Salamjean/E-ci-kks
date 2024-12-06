<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
<style>
   body {
    position: relative; /* Nécessaire pour le positionnement du pseudo-élément */
    margin: 0;
    height: 100vh;
}

body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url(assets/images/profiles/femme.png);
    background-size: cover;
    background-position: center;
    opacity: 0.3; /* Réduit l'opacité de l'image à 50% */
    z-index: -1; /* S'assure que l'image reste en arrière-plan */
}

    .logo1{
        position: absolute;
        margin-left: 75%;
        margin-top:2; 
        height: 110px;
        left: 40px;
    }
    .logo2{
        height: 130px;
        position: absolute;
        left: -50px;
        margin-left:0;
        margin-top:0; 
    }
    .logo{
        display: flex;
        justify-content: space-between;
       
    }
    .tete{
        text-align: center;
        font-family: calisto MT;
        font-size: 25px;
        color: #006;
        font-weight: bold;
    }

    .signature{
        position: absolute;
        bottom: 300px;
        right: 25px;
        font-size: 15px;
        font-weight: bold;
    }
    hr{
        position: absolute;
        bottom: 85px;
        width: 90%;
        border: 1px solid black;
        margin-left: 20px;
    }
    footer{
        position: absolute;
        bottom: 10px;
    }
</style>
</head>
<body>
    <header>
        <div class="logo_g">
            <div class="logo">
                <img src=assets/images/profiles/sante.jpg class="logo2" alt="Logo" >
                <img src=assets/images/profiles/gouv_ci.png class="logo1" alt="Logo" >
            </div>
        </div> <br><br><br><br><br><br><br><br>
            <h1 class="tete">CERTIFICAT MEDICAL DE NAISSANCE</h1>
    </header>
    <main>
        <div class="InfoNor">
            <p><strong> Sanitaire </strong>: {{ $NomHop }}</p>
            <p><strong>Ville/Commune de Naissance </strong>: Abobo</p>
            <p><strong>Date et Heure déclaration </strong>: {{ $naissHop->created_at }}</p>
            <p><strong>Numéro de déclaration </strong>: {{ $naissHop->codeCMN }}</p>
        </div>

        <div class="InfoImp">
            <p>Je soussigné(e) : Dr   {{ $sousadminNom }}  ,</p>
            <p>Certifie que Mme : {{ $naissHop->NomM }},</p>
            <p>a bien accouché dans notre établissement sanitaire le : {{ $naissHop->DateNaissance }} .</p>
            <p>De 1 enfant vivant, de sexe {{ $naissHop->sexe }}</p>
        </div>

        <div class="signature">
            <p>Fait à Abobo. Le {{ $naissHop->created_at }}</p>
            <p>Le Médecin : </p>
            <p>{{ $sousadminNom }}</p>
        </div>
    </main>
    <hr>
    <footer>
        134. Cet article stipule qu'une personne est coupable d'infractions en rapport avec la falsification,<br> la reproduction ou l'usage de faux documents dans des contextes où ces actes sont destinés à induire en erreur l'autorité publique ou des tiers. <br>Est puni de deux(02) à dix(10) ans et une amende de 200 000 à 2 000 000 de franc CFA.
    </footer>
</body>
</html>
