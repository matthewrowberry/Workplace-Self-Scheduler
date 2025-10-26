<select id=\"restrictionDropdown\" name=\"employee\"><option value=\"0\">Default</option><?php



                                                require '../config.php';

                                                try {
                                                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

                                                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                                    $sql = $conn->prepare("SELECT id, name FROM users");

                                                    $sql->execute();

                                                    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

                                                    foreach ($result as $row) {
                                                        $id = $row['id'];
                                                        $name = $row['name'];
                                                        echo "<option value=\\\"" . (string)$id . "\\\">" . (string)$name . "</option>";
                                                    }
                                                } catch (PDOException $e) {
                                                    echo "Connection failed: " . $e->getMessage();
                                                }


                                                $conn = null;



                                                ?>
</select>