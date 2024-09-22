exports.handler = async function(event, context) {
    const { name, email, message } = JSON.parse(event.body);
  
    const nodemailer = require('nodemailer');
  
    let transporter = nodemailer.createTransport({
      service: 'gmail', // Oder dein E-Mail-Service
      auth: {
        user: 'upluts@gmail.com', // Ersetze mit deiner E-Mail
        pass: 'dein-passwort'          // Ersetze mit deinem Passwort
      }
    });
  
    let mailOptions = {
      from: email,
      to: 'upluts@gmail.com', // Deine E-Mail-Adresse
      subject: `Neue Nachricht von ${name}`,
      text: message
    };
  
    try {
      await transporter.sendMail(mailOptions);
      return {
        statusCode: 200,
        body: JSON.stringify({ message: 'E-Mail erfolgreich gesendet!' })
      };
    } catch (error) {
      return {
        statusCode: 500,
        body: JSON.stringify({ error: 'Fehler beim Senden der E-Mail' })
      };
    }
  };
  