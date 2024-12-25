@extends('utilisateur.layouts.template')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    /* Styles pour le formulaire */
    .conteneurInfo {
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        margin: auto;
        animation: fadeIn 0.6s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    h1 {
        text-align: center;
        color: #1a5c58;
        margin-bottom: 1rem;
    }

    label {
        font-weight: bold;
        color: #1a5c58;
        margin-top: 1rem;
    }

    input[type="text"], input[type="file"] {
        width: 100%;
        padding: 0.8rem;
        margin-top: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #f9f9f9;
        outline: none;
    }

    button {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        font-weight: bold;
        background-color: #3e8e41;
        border: none;
        border-radius: 8px;
        color: #ffffff;
        cursor: pointer;
        margin-top: 2rem;
    }

    button:hover {
        background-color: #144d4b;
    }

    .hidden {
        display: none;
    }
</style>

<!-- Afficher les messages SweetAlert -->
@if (Session::get('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Succès',
        text: '{{ Session::get('success') }}',
        timer: 3000,
        showConfirmButton: false,
    });
</script>
@endif

@if (Session::get('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: '{{ Session::get('error') }}',
        timer: 3000,
        showConfirmButton: false,
    });
</script>
@endif

<!-- Contenu du formulaire -->
<div class="conteneurInfo">
    <h1>Demande d'Acte de Naissance</h1>
    <form id="naissanceForm" method="POST" action="{{ route('naissance.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Saisie du numéro de dossier médical -->
        <div class="form-group">
            <label for="dossierNum">Numéro de Dossier Médical</label>
            <input type="text" id="dossierNum" name="dossierNum" value="{{ old('dossierNum') }}" placeholder="Ex: CMN1411782251">
            @error('dossierNum')
            <span style="color: red">{{ $message }}</span>
            @enderror
        </div>

        <!-- Section masquée avec les informations récupérées -->
        <div id="infoDefunt" class="hidden">
            <div class="form-group">
                <label for="nomHopital">Nom de l'Hôpital</label>
                <input type="text" id="nomHopital" name="nomHopital" readonly>
            </div>

            <div class="form-group">
                <label for="nomDefunt">Nom et Prénom de la Mère</label>
                <input type="text" id="nomDefunt" name="nomDefunt" readonly>
            </div>

            <div class="form-group">
                <label for="dateNaiss">Nom et Prénom du Père</label>
                <input type="text" id="dateNaiss" name="dateNaiss" readonly>
            </div>

            <div class="form-group">
                <label for="lieuNaiss">Date de Naissance de l'Enfant</label>
                <input type="text" id="lieuNaiss" name="lieuNaiss" readonly>
            </div>

            <!-- Téléchargement des documents -->
            <div class="form-group">
                <label for="identiteDeclarant">Pièce d'Identité du Père</label>
                <input type="file" id="identiteDeclarant" name="identiteDeclarant">
                @error('identiteDeclarant')
                <span style="color: red">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="cdnaiss">Certificat de Déclaration de Naissance</label>
                <input type="file" id="cdnaiss" name="cdnaiss">
                @error('cdnaiss')
                <span style="color: red">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Boutons -->
        <button type="button" id="btnSuivant" onclick="validerFormulaire()">Suivant</button>
        <button type="submit" id="btnValider" class="hidden">Valider</button>
    </form>
</div>

<script>
    function validerFormulaire() {
        const dossierNum = $("#dossierNum").val();
        if (!dossierNum.trim()) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez entrer un numéro de dossier médical.',
            });
            return;
        }

        $.ajax({
            url: "{{ route('verifierCodeDM') }}",
            method: "POST",
            data: {
                codeCMN: dossierNum,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.existe) {
                    $("#nomHopital").val(response.nomHopital);
                    $("#nomDefunt").val(response.nomMere);
                    $("#dateNaiss").val(response.nomPere);
                    $("#lieuNaiss").val(response.dateNaiss);

                    $("#infoDefunt").removeClass("hidden");
                    $("#btnSuivant").addClass("hidden");
                    $("#btnValider").removeClass("hidden");
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Numéro de dossier incorrect.',
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Problème de connexion au serveur.',
                });
            }
        });
    }
</script>
@endsection
