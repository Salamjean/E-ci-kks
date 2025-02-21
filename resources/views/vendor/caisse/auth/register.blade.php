<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="Style.css">
    <link rel="icon" href="{{ asset('assets/images/profiles/E-ci-logo.png') }}">
    <title>Inscription d'un Admin</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-image: url({{ asset('assets/images/profiles/vendorbg.jpg') }});
            background-size: cover;
        }

        .form-control {
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            gap: 15px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            width: 450px;
            border-radius: 20px;
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.6);
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            text-align: center;
            color: #333;
        }

        .input-field {
            position: relative;
            width: 100%;
        }

        .input {
            width: 100%;
            outline: none;
            border-radius: 8px;
            height: 45px;
            border: 1.5px solid #ecedec;
            background: transparent;
            padding-left: 10px;
            transition: border 0.3s ease;
        }

        .input:focus {
            border: 1.5px solid #007bff;
        }

        .label {
            position: absolute;
            top: 15px;
            left: 10px;
            color: #ccc;
            transition: all 0.3s ease;
            pointer-events: none;
            z-index: 2;
        }

        .input:focus ~ .label,
        .input:valid ~ .label {
            top: -5px;
            left: 5px;
            font-size: 12px;
            color: #007bff;
            background-color: #ffffff;
            padding-left: 5px;
            padding-right: 5px;
        }

        .submit-btn {
            margin-top: 30px;
            height: 55px;
            background: #6777ef;
            border: 0;
            outline: none;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            border-radius: 11px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.15, 0.83, 0.66, 1);
        }

        .submit-btn:hover {
            box-shadow: 0px 0px 0px 2px #ffffff, 0px 0px 0px 4px rgba(0, 0, 0, 0.1);
        }

        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<form class="form-control" method="POST" action="{{ route('caisse.validate', $email) }}">
    @method('post')
        @csrf
  <div class="row" style="width:100%; justify-content:center">
    @if (Session::get('success1')) <!-- Pour la suppression -->
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Suppression réussie',
                text: '{{ Session::get('success1') }}',
                showConfirmButton: true,  // Afficher le bouton OK
                confirmButtonText: 'OK',  // Texte du bouton
                background: '#ffcccc',   // Couleur de fond personnalisée
                color: '#b30000'          // Texte rouge foncé
            });
        </script>
    @endif

    @if (Session::get('success')) <!-- Pour la modification -->
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Action réussie',
                text: '{{ Session::get('success') }}',
                showConfirmButton: true,  // Afficher le bouton OK
                confirmButtonText: 'OK',  // Texte du bouton
                background: '#ccffcc',   // Couleur de fond personnalisée
                color: '#006600'          // Texte vert foncé
            });
        </script>
    @endif

    @if (Session::get('error')) <!-- Pour une erreur générale -->
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: '{{ Session::get('error') }}',
                showConfirmButton: true,  // Afficher le bouton OK
                confirmButtonText: 'OK',  // Texte du bouton
                background: '#f86750',    // Couleur de fond rouge vif
                color: '#ffffff'          // Texte blanc
            });
        </script>
    @endif
</div>
    <p class="title">Definisez vos accès</p>

    @if (Session::get('success'))
        <div class="success-message">{{ Session::get('success') }}</div>
    @endif

    <div class="flex-column">
        <label>Email </label>
      </div>
      <div class="inputForm">
        
        <input style="background-color: rgb(233, 228, 228)" placeholder="Email@exemple.com" class="input" type="email" value="{{ $email }}" name="email" readonly/>
      </div>
      @error('email')
            <div class="text-danger" style="color: red; text-align:center">{{ $message }}</div>
        @enderror
      <div class="flex-column">
        <label>Code de validation </label>
      </div>
      <div class="inputForm">
        <input  class="input" type="text" value="{{ old('code') }}" name="code" />
        @error('code')
        <div class="text-danger" style="color: red; text-align:center">{{ $message }}</div>
    @enderror
      </div>
       
   
    
      <div class="flex-column">
        <label>Mot de passe </label>
      </div>
      <div class="inputForm">
       
          <path
            d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"
          ></path>
          <path
            d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"
          ></path>
        </svg>
        <input placeholder="Entrez votre mot de passe" class="input" type="password" id="password" name="password" />
        @error('password')
            <div class="text-danger" style="color: red; text-align:center">{{ $message }}</div>
        @enderror
    </div>
      <div class="flex-column">
        <label>Confirmer Mot de passe </label>
      </div>
      <div class="inputForm">
       
          <path
            d="m336 512h-288c-26.453125 0-48-21.523438-48-48v-224c0-26.476562 21.546875-48 48-48h288c26.453125 0 48 21.523438 48 48v224c0 26.476562-21.546875 48-48 48zm-288-288c-8.8125 0-16 7.167969-16 16v224c0 8.832031 7.1875 16 16 16h288c8.8125 0 16-7.167969 16-16v-224c0-8.832031-7.1875-16-16-16zm0 0"
          ></path>
          <path
            d="m304 224c-8.832031 0-16-7.167969-16-16v-80c0-52.929688-43.070312-96-96-96s-96 43.070312-96 96v80c0 8.832031-7.167969 16-16 16s-16-7.167969-16-16v-80c0-70.59375 57.40625-128 128-128s128 57.40625 128 128v80c0 8.832031-7.167969 16-16 16zm0 0"
          ></path>
        </svg>
        <input placeholder="Confirmer votre mot de passe" class="input" type="password" id="password" name="confirme_password" />
        @error('confirme_password')
        <div class="text-danger" style="color: red; text-align:center">{{ $message }}</div>
    @enderror
    </div>
   

        <div class="flex-column">
            <label>Photo de Profil</label>
        
        <div class="inputForm">
            <input type="file" name="profile_picture" accept="image/*" class="input" />
            @error('profile_picture')
            <div class="text-danger" style="color: red; text-align:center">{{ $message }}</div>
            @enderror
        </div>
      </div>
      <button class="button-submit">Valider</button>
</form>
<script>
  document.addEventListener('DOMContentLoaded', function() {
      @if (Session::has('success1'))
          Swal.fire({
              icon: 'success',
              title: 'Suppression réussie',
              text: '{{ Session::get('success1') }}',
              confirmButtonText: 'OK'
          });
      @endif

      @if (Session::has('success'))
          Swal.fire({
              icon: 'success',
              title: 'Action réussie',
              text: '{{ Session::get('success') }}',
              confirmButtonText: 'OK'
          });
      @endif

      @if (Session::has('error'))
          Swal.fire({
              icon: 'error',
              title: 'Erreur',
              text: '{{ Session::get('error') }}',
              confirmButtonText: 'OK'
          });
      @endif
  });
</script>

<!-- Scripts pour Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
      $('#name').select2({
          placeholder: "Sélectionnez une commune",
          allowClear: true,
          width: '100%'
      });
  });
</script>
</body>
</html>

