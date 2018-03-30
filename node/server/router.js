//Importacion de librerias requeridas
const express 		= 	require("express"),
			MongoClient	=  	require("mongodb").MongoClient,
			url 				= 	"mongodb://localhost:27017/nuevaDB",
			router			=  	express.Router();

// Funcion que muestra los eventos asociados al usuario
router.get("/all", (req, res) =>
	{
		//conecto la base de datos
		if (req.session.email_user)
			{
				MongoClient.connect(url, (err, db) =>
					{
						var base = db.db("nuevaDB");
						var coleccion = base.collection("eventos");
						coleccion.find({fk_usuario: req.session.email_user}).toArray( (error, eventos) =>
							{
								if (error) throw erro;
								res.send(eventos);
							});
		  			db.close();
					});
			}
			else
				{
					res.send("noLOGIN");
				}

	});

//Script para guardar un nuevo evento en la Agenda
router.post("/new", (req, res) =>
	{
		if (req.session.email_user)
			{
				MongoClient.connect(url, (err, db) =>
					{
						if (err) throw err;
						var base = db.db("nuevaDB");
						var coleccion = base.collection("eventos");
						var nID = req.body.title.trim() + Math.floor(Math.random(0),100 )+1;

						coleccion.save(
							{
								_id:nID,
								title:req.body.title,
								start:req.body.start,
								end: req.body.end,
								start_hour: req.body.start_hour,
								end_hour: req.body.end_hour,
								fk_usuario: req.session.email_user
							});

						db.close();
					});
			}
			else
				{
					res.send("noLOGIN");
				}
	});

//Script para borrar un evento de la Agenda
router.post("/delete", (req, res) =>
	{
		MongoClient.connect(url, (err, db) =>
			{
				if (err) throw err;
				var base = db.db("nuevaDB");
				var coleccion = base.collection("eventos");

				try
					{
						coleccion.remove(
							{
								_id:req.body.id,
								fk_usuario: req.session.email_user
							});
						res.send("Evento borrado con exito!");
					}
					catch (err)
						{
							res.send(err);
						}
					db.close();
			});
	});

//Script para actualizar un evento de la Agenda
router.post("/update", (req, res) =>
	{
		MongoClient.connect(url, (err, db) =>
			{
				if (err) throw err;
				var base = db.db("nuevaDB");
				var coleccion = base.collection("eventos");
				try
					{
						coleccion.update(
							{
								_id:req.body.id
							},
							{
								$set:
									{
										start: req.body.start,
										end: req.body.end,
										end_hour: req.body.end_hour,
										start_hour: req.body.start
									}
							});
						res.send("El evento se a cambiado con exito!");
					}
					catch(e)
						{
							console.log(e);
						}

				db.close();
			});
	});

//Script para salir de la sesion
router.get("/logout", (req,res) =>
	{
		req.session.email_user= false;
		req.session.destroy( (err) =>
			{
  			res.send("adios");
			})
	});

//Se exportan las rutas definidas
module.exports = router;
