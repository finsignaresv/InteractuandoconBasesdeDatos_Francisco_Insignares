db.createUser( { user: "newuser",
                 pass: "12345",
                 customData: { userId: 12345 },
                 roles: [ { role: "readWrite", db: "nuevaDB" } ] })
