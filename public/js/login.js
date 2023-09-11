axios.defaults.baseURL = 'http://localhost:8000/api'

const button = document.getElementById('button');
const alert = document.getElementById('alert');
const alerts = document.getElementById('alerts');
button.addEventListener('click', login);

async function login() {
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    await axios.post("/auth/login", {
        email, password,
    })
        .then((response) => {
            alerts.innerHTML = '';

            if (response.data.success) {
                alert.classList.remove('visible');
                alert.classList.toggle('invisible');

                localStorage.setItem('access_token', response.data.data.access_token);
                window.location.href = "http://localhost:8000/";
            } else {

            }

        })
        .catch(({response}) => {
            alerts.innerHTML = ''
            alert.classList.remove('invisible')
            alert.classList.toggle('visible')

            const messages = response.data.data
            const message = response.data.message
            if (messages) {
                Object.keys(messages).map((message) => {
                    const alert = document.createElement('p')
                    alert.innerHTML = messages[message]
                    alerts.appendChild(alert)
                });
            } else {
                const alert = document.createElement('p')
                alert.innerHTML = message
                alerts.appendChild(alert)
            }
        })
}
