const axios = require('axios')
const bodyParser = require('body-parser')
const express = require('express')
const app = express()

const adapter = require('axios/lib/adapters/http')

const instance = axios.create({
  baseURL: 'http://localhost:3001',
  proxy: false
});

app.use(express.static('public'))
app.use(bodyParser.urlencoded({ extended: true }))
app.set('view engine', 'ejs')

app.get('/', function (req, res) {
  res.render('index', {success: null, error: null});
})

app.post('/', function (req, res) {
  var success = null;
  var error = null;

  instance.post('/auth', {
    username: req.body.username,
    password: req.body.password
  })
  .then(response => {
    if (response.status) {
      success = "Success"
    } else {
      error = "Wrong Credentials"
    }

    res.render('index', {success, error});
  })
  .catch(error => {
    res.render('index', {success, error});
  })

  res.render('index', {success, error});
})

app.get('/recover-password', function (req, res) {

  const { data } = instance.post( '/recover-password', { adapter } )
  .then(response => {
    console.log(response)
  })
  .catch(error => {
    console.log(error)
    //res.render('index', {success, error});
  })

  res.render('recover-password', {success: null, error: null});
})

app.post('/recover-password', function (req, res) {
  var success = null;
  var error = null;

  console.log('POST ->')
  instance.post('/recover-password', {
    email: req.body.email,
  })
  .then(response => {
    console.log('THEN', response)
    if (response.status) {
      success = 'Email sent!'
    } else {
      error = 'Something went wrong.'
    }

    res.render('index', {success, error});
  })
  .catch(error => {
    console.log('CATCH', error)
    //res.render('index', {success, error});
  })

  res.render('recover-password', {success, error});
})

app.listen(3000, function () {
  console.log('Example app listening on port 3000!')
})
