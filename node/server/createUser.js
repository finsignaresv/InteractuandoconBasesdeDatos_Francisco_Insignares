db.createUser( { user: "newuser",
                 pwd: "12345",
                 customData: { employeeId: 12345 },
                 roles: [ { role: "readWrite", db: "nuevaDB" } ] })
