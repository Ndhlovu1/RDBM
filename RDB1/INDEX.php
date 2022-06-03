<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=joke','joke','toor123');
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec('set names "utf8"');

}//end connect to DB

catch(PDOException $e) {
    $error = 'Unable to connect to Database'. $e->getMessage();
    include 'error.html.php';
    exit();
}//end connect catch


//The code for adding a joke that is used to also get the code from the 
if(isset($_GET['addjoke'])) 
{
    include 'form.html.php';
    exit();
    }//end the check on addjoke

//code for collecting the joke that has been swet
if(isset($_POST['joketext'])){

    try{
        $sql = 'INSERT INTO joke SET 
        joketext = :joketext, 
        jokedate = CURDATE()';
        $s = $pdo->prepare($sql);
        $s ->bindValue(':joketext', $_POST['joketext']);
        $s->execute();
    }//end joketext try

    catch(PDOException $e){
        $error = 'Error Adding submitted joke: '. $e->getMessage();
        include 'error.html.php';
        exit();
        
    }//End the addjoketext Try Catch

//Ask server to view the updated list of the jokes.
    header('Location: .');
    exit();
}//end joktext if

//#############################################

//Making the delete buton to work
if(isset($_GET['deletejoke'])){
    try {
        $sql = 'DELETE FROM joke WHERE id = :id';
        //Create the ID placeholder
        $s = $pdo ->prepare($sql);
        $s -> bindValue(':id', $_POST['id']);
    //The bV is combined/bound so that to the placeholder and the query.
        $s->execute();
    //The above code is running the code
    }//end try from if butn

    catch(PDOException $e){
        $error = 'Error Deleting Joke: '. $e->getMessage();
        include 'error.html.php';
        exit();
}//end butn catch

    /* The below(Header) code is used to find the location
    The PHP HEADER is used to ask the browser to send the updated joke list
     */
    header('Location: .');    
    exit();
    
}//end delete button if


try {
    $sql = 'SELECT joke.id, joketext, name, email
        FROM joke INNER JOIN author
            ON authorid = author.id';
    $result = $pdo->query($sql);
}//end try to fetch the collective data

catch (PDOException $e){
    $error = "Error fetching jokes" . $e->getMessage();
    include 'error.html.php';
    exit();

}//end the fetch catch

//Use a foreach instead of a while loop to process the data 
foreach( $result as $row) {
    $jokes[] = array (
        'id' => $row['id'],
        'text' => $row['joketext'],
        'name' => $row['name'],
        'email' => $row['email']
    );

}//end the for each

include 'jokes.html.php';

?>