<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule of the movies</title>
</head>
<style>

</style>
<body>
    <div>
        <h1>Schedule of the movies</h1>
    </div>
    <div>
        <table>
            <tr>
                <th>Movie</th>
                <th>Time</th>
                <th>Date</th>
            </tr>
            <?php
                $conn = mysqli_connect("localhost", "root", "", "cinema");
                $sql = "SELECT * FROM schedule";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    while($row = mysqli_fetch_assoc($result)){
                        echo "<tr><td>". $row["movie"]. "</td><td>". $row["time"]. "</td><td>". $row["date"]. "</td></tr>";
                    }
                    echo "</table>";
                }else{
                    echo "0 result";
                }
                mysqli_close($conn);
           ?>
        </table>
    </div>
</body>
<script>

</script>
</html>