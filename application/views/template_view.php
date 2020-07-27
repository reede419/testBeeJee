<?php
$auth = $data['auth'] && $data['auth']['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script src="/js/jquery-1.6.2.js" type="text/javascript"></script>
</head>
<body>

<?php
if(!$auth) { ?>
    <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</button>
<?php }
?>

<div id="id01" class="modal">

    <form class="modal-content animate" method="post" action="/user">
        <div class="imgcontainer">
            <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">×</span>
            <img src="images/img_avatar2.png" alt="Avatar" class="avatar">
        </div>

        <div class="container">
            <label for="uname"><b>Username</b></label>
            <input type="text" placeholder="Enter Username" name="username" required>

            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="password" required>

            <button type="submit">Login</button>
        </div>
    </form>
</div>

<h1>Task list</h1>
<div class="sort">
Order by:<br/>
    <a href="#" class="name-order-status" data-sort="user_name">name</a><br/>
    <a href="#" class="email-order-status" data-sort="email">email</a><br/>
    <a href="#" class="status-order-status" data-sort="done">status</a><br/>
</div>
<div class="order">
    Order by:<br/>
    <a href="#" data-order="asc">ASC</a><br/>
    <a href="#" data-order="desc">DESC</a><br/>
</div>
<br>
<div id="newTask">
    <div class="new_task">
        <input type="text" placeholder="user name" name="user_name">
        <input type="email" placeholder="email" name="email">
        <input type="text" placeholder="text" name="text">
        <a class="add" href="#">Add</a>
    </div>
</div>
<br>
<ul id="myUL">
    <?php
    foreach($data['tasks']['tasks'] as $row)
    {
        if($auth) {
            $taskRow = '<li class="'.($row['done'] ? 'checked' : '').'" data-id="'.$row['id'].'">' . $row['user_name']
                . ' (' . $row['email'] . ') '
                . '<input type="text" value="'.$row['text'].'"> '
                . '<a class="update">Update</a>'
                . '</li>';
        }else{
            $taskRow = '<li class="'.($row['done'] ? 'checked' : '').'" data-id="'.$row['id'].'">' . $row['user_name']
                . ' (' . $row['email'] . ')   '
                . $row['text']
                . ' </li>';
        }
        echo $taskRow;
    }

    ?>
</ul>
<div class="pagination">
<?php
for($i = 1; $i<=round($data['tasks']['count']['0']/3); $i++){
    echo '<a class="pagination" data-page="'.$i.'" href="#">'.$i.'</a>';
}
?>
</div>

<script>
    function updateURLParameter(url, param, paramVal){
        let newAdditionalURL = "";
        let tempArray = url.split("?");
        let baseURL = tempArray[0];
        let additionalURL = tempArray[1];
        let temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (let i=0; i<tempArray.length; i++){
                if(tempArray[i].split('=')[0] != param){
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }

        let rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
    }

    $('.pagination>a').click(function(event){
        event.preventDefault();
        window.location.href = updateURLParameter(window.location.href, 'page', $(this).attr('data-page'));
    })

    $('.sort>a').click(function(event){
        event.preventDefault();
        window.location.href = updateURLParameter(window.location.href, 'sort', $(this).attr('data-sort'));
    })

    $('.order>a').click(function(event){
        event.preventDefault();
        window.location.href = updateURLParameter(window.location.href, 'order', $(this).attr('data-order'));
    })

    $('.update').click(function(event){
        event.preventDefault();
        let newText = $(event.target).parent('li').children('input').val();
        let id = $(event.target).parent('li').attr('data-id');
        $.post("/main/update", {
            "id": id,
            "text": newText
        });
    });

    $('.add').click(function(event){
        event.preventDefault();
        let username = $(event.target).parent('div').children('input[name="user_name"]').val();
        let email = $(event.target).parent('div').children('input[name="email"]').val();
        let text = $(event.target).parent('div').children('input[name="text"]').val();
        if(!username || !email || !text) {
            alert('Please fill all fields!')
        }else{
            $.post("/main/add", {
                "user_name": username,
                "email": email,
                "text": text
            });
            $('.new_task input').val('')
        }
    });

    let myNodelist = document.getElementsByTagName("LI");

    let close = document.getElementsByClassName("close");
    let i;
    for (i = 0; i < close.length; i++) {
        close[i].onclick = function() {
            let div2 = this.parentElement.parentElement.parentElement;
            div2.style.display = "none";
        }
    }

    let list = document.querySelector('ul');
    list.addEventListener('click', function(ev) {
        if (ev.target.tagName === 'LI' && "<?php echo $auth; ?>") {
            ev.target.classList.toggle('checked');
            $.post("/main/toggle", {"id": $(ev.target).attr('data-id')});
        }
    }, false);

    let modal = document.getElementById('id01');

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>

</body>
</html>



