const sendgrid = require('@sendgrid/mail');

exports.handler = async function(event, context) {
  const { name, email, message } = JSON.parse(event.body);

  // Setze deinen SendGrid API-Schlüssel
  sendgrid.setApiKey(process.env.SENDGRID_API_KEY);

  const msg = {
    to: 'upluts@gmail.com', // Deine E-Mail-Adresse
    from: 'noreply@deinedomain.com', // Die Absenderadresse
    subject: `Neue Nachricht von ${name}`,
    text: `
      Name: ${name}
      E-Mail: ${email}
      Nachricht: ${message}
    `,
  };

  try {
    await sendgrid.send(msg);
    return {
      statusCode: 200,
      body: JSON.stringify({ message: 'Nachricht erfolgreich gesendet.' }),
    };
  } catch (error) {
    console.error(error);
    return {
      statusCode: 500,
      body: JSON.stringify({ error: 'Fehler beim Senden der Nachricht.' }),
    };
  }
};
