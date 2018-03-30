//Definicion de db para que pueda ser reconocido dentro del script
db = db.getSiblingDB('nuevaDB');
print("Se ha creado la base de datos " + db + " el usuario: newuser@mail.com con el password: 12345");

//Creacion del usuario newuser dentro de la base de datos nuevaDB
db.usuarios.save({email:"newuser@mail.com",nombre:"newuser",password:"12345"});
