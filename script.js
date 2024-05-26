$(document).ready(function() {
 
    var limit = 3;

    function loadUsers(page = 1) {
        $.ajax({
            url: 'ajax.php', 
            type: 'POST', 
            data: { 
                action: 'read', 
                page: page, 
                limit: limit 
            },
            success: function(response) {
                
                $('#userTable').html(response);
            },
            error: function(xhr, status, error) {
               
                console.error(error);
            }
        });
    }

    loadUsers();

    $('#searchInput').on('input', function() {
        var input = $(this).val().trim();
        if (input === '') {
            loadUsers();
        } else {
            $.ajax({
                url: 'ajax.php', 
                type: 'POST', 
                data: {
                    action: 'search', 
                    input: input, 
                    page: 1,
                    limit: limit 
                },
                success: function(response) {
                    
                    $('#userTable').html(response);
                },
                error: function(xhr, status, error) {
                    
                    console.error(error);
                }
            });
        }
    });

    $('#userTable').on('click', '.editBtn', function() {
        var userId = $(this).data('id');
        var userName = $(this).data('name');
        var userEmail = $(this).data('email');

        $('#userId').val(userId);
        $('#name').val(userName);
        $('#email').val(userEmail);

        $('#userModal').modal('show');
    });

    $('#userTable').on('click', '.deleteBtn', function() {
        var userId = $(this).data('id');

        if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
            $.ajax({
                url: 'ajax.php', 
                type: 'POST', 
                data: {
                    action: 'delete', 
                    id: userId 
                },
                success: function(response) {
                    
                    console.log(response);
                    loadUsers();
                },
                error: function(xhr, status, error) {
                   
                    console.error(error);
                }
            });
        }
    });

    $('#userForm').submit(function(e) {
        e.preventDefault();

        var userId = $('#userId').val();
        var userName = $('#name').val();
        var userEmail = $('#email').val();

        
        $.ajax({
            url: 'ajax.php', 
            type: 'POST', 
            data: {
                action: userId ? 'update' : 'create', 
                id: userId, 
                name: userName, 
                email: userEmail 
            },
            success: function(response) {
                
                console.log(response);
                loadUsers();
                $('#userModal').modal('hide');
                $('#userForm')[0].reset();
            },
            error: function(xhr, status, error) {
                
                console.error(error);
            }
        });
    });

   
    $('#userTable').on('click', '.page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        
        loadUsers(page);
    });
});