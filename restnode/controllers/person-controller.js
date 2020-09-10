const mysql = require('../mysql');

exports.getPersons = async (req, res, next) => {
    try {
        const result = await mysql.execute("SELECT * FROM persons;")
        const response = {
            length: result.length,
            persons: result.map(pers => {
                return {
                    idpersons: pers.idpersons,
                    name: pers.name,
                    position: pers.position,
                    npr: pers.npr,
                    photo: pers.photo,
                    request: {
                        type: 'GET',
                        description: 'Retorna os detalhes de uma pessoa específico',
                        url: process.env.URL_API + 'persons/' + pers.idpersons
                    }
                }
            })
        }
        return res.status(200).send(response);
    } catch (error) {
        return res.status(500).send({ error: error });
    }
};

exports.postPerson = async (req, res, next) => {
    try {
        const query = 'INSERT INTO persons (name, position, npr, photo) VALUES (?,?,?,?)';

        const result = await mysql.execute(query, [
            req.body.name,
            req.body.position,
            req.body.npr,
            req.file.path
        ]);

        const response = {
            message: 'Pessoa inserida com sucesso',
            createdPerson: {
                idpersons: result.idpersons,
                name: req.body.name,
                position: req.body.position,
                npr: req.body.npr,
                photo: req.file.path,
                request: {
                    type: 'GET',
                    description: 'Retorna todas as pessoas',
                    url: process.env.URL_API + 'persons'
                }
            }
        }

        return res.status(201).send(response);
    } catch (error) {
        return res.status(500).send({ error: error });
    }
};

exports.getPersonDetail = async (req, res, next)=> {
    try {
        const query = 'SELECT * FROM persons WHERE idpersons = ?;';
        const result = await mysql.execute(query, [req.params.idpersons]);

        if (result.length == 0) {
            return res.status(404).send({
                message: 'Não foi encontrado pessoa com este ID'
            })
        }
        const response = {
            person: {
                idpersons: result[0].idpersons,
                name: result[0].name,
                position: result[0].position,
                npr: result[0].npr,
                photo: result[0].photo,
                request: {
                    type: 'GET',
                    description: 'Retorna todas as pessoas',
                    url: process.env.URL_API + 'persons'
                }
            }
        }
        return res.status(200).send(response);
    } catch (error) {
        return res.status(500).send({ error: error });
    }
};

exports.updatePerson = async (req, res, next) => {

    try {
        const query = ` UPDATE persons
                           SET name         = ?,
                               position        = ?,
                               npr        = ?
                         WHERE idpersons    = ?`;
        await mysql.execute(query, [
            req.body.name,
            req.body.position,
            req.body.npr,
            req.params.idpersons
        ]);
        const response = {
            message: 'Pessoa atualizada com sucesso',
            upatedPerson: {
                idpersons: req.params.idpersons,
                name: req.body.name,
                position: req.body.position,
                npr: req.body.npr,
                request: {
                    type: 'GET',
                    description: 'Retorna os detalhes de uma pessoa específica',
                    url: process.env.URL_API + 'persons/' + req.params.idpersons
                }
            }
        }
        return res.status(202).send(response);
    } catch (error) {
        return res.status(500).send({ error: error });
    }
};

exports.deletePerson = async (req, res, next) => {
    try {
        const query = `DELETE FROM persons WHERE idpersons = ?`;
        await mysql.execute(query, [req.params.idpersons]);

        const response = {
            message: 'Pessoa removida com sucesso',
            request: {
                type: 'POST',
                description: 'Insere uma pessoa',
                url: process.env.URL_API + 'persons',
                body: {
                    name: 'String',
                    position: 'String',
                    npr: 'String'
                }
            }
        }
        return res.status(202).send(response);

    } catch (error) {
        return res.status(500).send({ error: error });
    }
};

exports.updateImage = async (req, res, next) => {
    try {
        const query = ` UPDATE persons
                           SET photo         = ?
                         WHERE idpersons    = ?`;
        await mysql.execute(query, [
            req.file.path,
            req.params.idpersons
        ]);

        const response = {
            message: 'Imagem Pessoa atualizada com sucesso',
            upatedPerson: {
                idpersons: req.params.idpersons,
                photo: req.file.path,
                request: {
                    type: 'GET',
                    description: 'Retorna os detalhes de uma pessoa específica',
                    url: process.env.URL_API + 'persons/' + req.params.idpersons
                }
            }
        }
        return res.status(202).send(response);
    } catch (error) {
        return res.status(500).send({ error: error });
    }
};
