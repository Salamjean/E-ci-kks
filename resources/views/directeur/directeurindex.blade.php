@extends('directeur.layouts.template')

@section('content')
    <title>Liste des docteurs</title>

    <!-- Insertion de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Styling global */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 0;
            color: #333;
        }

        .container {
            width: 95%;
            margin-left: 0 3%;
            justify-content: center;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: black;
            margin-bottom: 20px;
            font-size: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .header .search-bar {
            display: flex;
            align-items: center;
            width: 70%;
        }

        .header input[type="text"] {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 25px;
            outline: none;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .header input[type="text"]:focus {
            border-color: #009efb;
            box-shadow: 0 0 5px rgba(0, 158, 251, 0.5);
        }

        .add-patient {
            background-color: #009efb;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        .add-patient i {
            margin-right: 8px;
        }

        .add-patient:hover {
            background-color: #007acd;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead th {
            background-color: #009efb;
            color: white;
            padding: 10px;
            text-align: left;
        }

        table tbody tr {
            background-color: #f9f9f9;
            border-bottom: 1px solid #dddddd;
            transition: background-color 0.3s ease;
        }

        table tbody tr:hover {
            background-color: #f1faff;
        }

        table tbody td {
            padding: 10px;
        }

        table tbody td:last-child {
            text-align: center;
        }

        button {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 16px;
        }

        a .edit{
            color: #28a745;
            transition: color 0.3s ease;
        }

        a .delete{
            color: #dc3545;
            transition: color 0.3s ease;
        }

        .edit {
            color: #28a745;
            transition: color 0.3s ease;
        }

        .edit:hover {
            color: #1e7e34;
        }

        .delete {
            color: #dc3545;
            transition: color 0.3s ease;
        }

        .delete:hover {
            color: #c82333;
        }
    </style>
</head>
<body>
    <div class="row" style="width:100%; justify-content:center">
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

        <div class="container col-11">
            <h1>Liste des docteurs de l'hôpital</h1>
        
            <table id="patients-table" class="display">
                <thead class="text-center">
                    <tr class="text-center">
                        <th class="text-center">Nom</th>
                        <th class="text-center">Prénoms</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Contact</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sousadmins as $sousadmin)
                    <tr class="text-center">
                        <td>{{ $sousadmin->name }}</td>
                        <td>{{ $sousadmin->prenom }}</td>
                        <td>{{ $sousadmin->email }}</td>
                        <td>{{ $sousadmin->description }}</td>
                        <td>{{ $sousadmin->contact }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucun docteur inscrire</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <script>
            document.getElementById('search').addEventListener('keyup', function() {
                const filter = this.value.toLowerCase();
                const rows = document.querySelectorAll('#patients-table tbody tr');
        
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const match = Array.from(cells).some(cell => 
                        cell.textContent.toLowerCase().includes(filter)
                    );
                    row.style.display = match ? '' : 'none';
                });
            });
        </script>
    <script>
        $(document).ready(function() {
            $('#patients-table').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 15, 20],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.6/i18n/French.json"
                },
                dom: 'rt<"bottom"lp>'
            });

            // Recherche personnalisée
            $('#search').on('input', function() {
                $('#patients-table').DataTable().search(this.value).draw();
            });
        });
    </script>

@endsection
<script>
    function showImage(imageElement) {
      const modalImage = document.getElementById('modalImage');
  
      // Vérifier si l'image utilise déjà la valeur de remplacement (image par défaut)
      if (imageElement.src.includes('assets/images/profiles/bébé.jpg')) {
          modalImage.src = imageElement.src; // Utiliser l'image par défaut
      } else {
          modalImage.src = imageElement.src; // Utiliser l'image actuelle (valide)
      }
  }
  
  </script>