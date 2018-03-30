//Importacion de librerias requeridas
const BodyParser 	=  require("body-parser"),
			express    	=  require("express"),
			MongoClient	=  require("mongodb").MongoClient,
			session    	=  require("express-session"),
 			http       	=  require("http"),
			events     	=  require("./router");

//Declaracion de variables
// Variables de la base datos
const	url 				= "mongodb://localhost:27017/nuevaDB",
			PORT				= 3000,
			app 				= express();

//Configuracion de las librerias necesarias
app.use(BodyParser.json());
app.use(BodyParser.urlencoded(
	{
		extended: true
	}));
app.use(express.static("client"));
app.use(session(
	{
		secret: "FI",//puede ir cualquier cosa
		resave: false,
		saveUninitialized: false
	}));


//Creacion del servidor
http.createServer(app);

//Escuchando el inicio de sesion
app.post("/login", (req, res) =>
	{
		//Almacenamiento de los datos del usuario en variables
		var user = req.body.user;
		var pass = req.body.pass;
		//Conexion con la base de datos
		MongoClient.connect(url, (err, db) =>
			{
				if (err)throw err; //Gestion del error
				var base = db.db("nuevaDB");
				var coleccion = base.collection("usuarios");
				coleccion.findOne(
					{
						email: user,
						password: pass}, (error, user) =>
							{
								if (error) throw error; //Gestion del error
								if (user)
									{
										req.session.email_user = user.email;
										res.send("Validado");
									}
									else
										{
											res.send("Usuario o contraseña no son correctos.");
										}
							});
		  	db.close();

			});
	});

app.use("/events", events);
//Escucha el servidor en el puerto definido
app.listen(PORT, () =>
	{
		console.log("El servidor de nuevaDB está corriendo por el puerto: " + PORT);
	});
