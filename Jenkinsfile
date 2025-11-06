pipeline {
    agent any

    environment {
        PHP = 'C:\\xampp\\php\\php.exe'      // PHP executable
        XAMPP_PATH = 'C:\\xampp'             // XAMPP root folder
        HTDOCS_PATH = 'C:\\xampp\\htdocs\\projectEngineering\\TicketMaster' // Deployment folder
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Checking out code from GitHub..."
                git branch: 'main', url: 'https://github.com/veronica-tes06/TicketMaster.git'
            }
        }

        stage('Build') {
            steps {
                script {
                    if (fileExists('composer.json')) {
                        echo "Installing dependencies with Composer..."
                        bat "composer install"
                    } else {
                        echo "No composer.json found. Skipping dependency installation."
                    }
                }
            }
        }

        stage('Test') {
            steps {
                echo "Starting XAMPP (Apache + MySQL)..."
                bat "${XAMPP_PATH}\\xampp_start.exe"

                echo "Running PHPUnit tests..."
                bat "\"${PHP}\" vendor\\bin\\phpunit --configuration phpunit.xml"
            }
        }

        stage('Deploy') {
            steps {
                echo "Deploying PHP application to htdocs..."
                bat "xcopy /Y /E * ${HTDOCS_PATH}\\"
            }
        }
    }

    post {
        success {
            echo "Pipeline completed successfully!"
            bat "${XAMPP_PATH}\\xampp_stop.exe"
        }
        failure {
            echo "Pipeline failed!"
            bat "${XAMPP_PATH}\\xampp_stop.exe"
            mail to: 'a00320733@student.tus.ie, a00317717@student.tus.ie, a00322305@student.tus.ie, a00320562@student.tus.ie',
            subject: "Build Failed: ${env.JOB_NAME} #${env.BUILD_NUMBER}", 
            body: "Check Jenkins logs: ${env.BUILD_URL}" 
        }
    }
}
