<?php declare(strict_types = 1);

require __DIR__ . '/../src/Bootstrap.php';
use App\User;
use App\UserList;

$newUser = new User('alex', 'kostykevich', '1996-10-10', 'Minsk', 1);
echo $newUser->getFormatUser(age: true, sex: true)->sex."</br>";
User::deleteUserById($newUser->id);
echo User::dateToAge(6)."</br>";
echo User::intToStringSex(3)."</br>";
print_r(User::getUserById(26));


$newUserList = new UserList('kostykevich');
$newUserList->getArrayUsersInstances();
$newUserList->deleteUsers();
