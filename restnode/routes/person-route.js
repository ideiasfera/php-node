const express = require('express');
const router = express.Router();
const multer = require('multer');
const login = require('../middleware/login');

const PersonsController = require('../controllers/person-controller');

const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, './uploads/');
    },
    filename: function(req, file, cb) {
		cb(null, new Date().toISOString().replace('-', '').replace('-', '').replace('T', '').replace(':', '').replace(':', '').replace('.', '').replace('Z', '') + file.originalname);
    }
});

const fileFilter = (req, file, cb) => {
    if (file.mimetype === 'image/jpeg' || file.mimetype === 'image/png') {
        cb(null, true);
    } else {
        cb(null, false);
    }
}

const upload = multer({
    storage: storage,
    limits: {
        fileSize: 1024 * 1024 * 5
    },
    fileFilter: fileFilter
});

router.get('/', PersonsController.getPersons);
router.post(
    '/',
    login.required,
    upload.single('image'),
    PersonsController.postPerson
);
router.get('/:idpersons', PersonsController.getPersonDetail);
router.patch('/:idpersons', login.required, PersonsController.updatePerson);
router.delete('/:idpersons', login.required, PersonsController.deletePerson);

router.patch(
    '/:idpersons/image',
    login.required,
    upload.single('image'),
    PersonsController.updateImage
);

module.exports = router;