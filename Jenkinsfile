pipeline {
    agent any

    environment {
        PHP = 'C:\\xampp\\php\\php_new\\php.exe'      // PHP executable
        XAMPP_PATH = 'C:\\xampp'             // XAMPP root folder
        HTDOCS_PATH = 'C:\\xampp\\htdocs\\projectEngineering\\TicketMaster' // Deployment folder
    }

    stages {
        stage('Checkout Code') {
            steps {
                echo "Checking out code from GitHub..."
                git branch: 'pseudo-main', url: 'https://github.com/veronica-tes06/TicketMaster.git'
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
                echo "Starting Apache and MySQL services..."
                bat "net start Apache2.4 || echo Apache already running"
                bat "net start MySQL || echo MySQL already running"
                
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
        always {
            echo "Stopping services..."
            bat "net stop Apache2.4 || echo Apache already stopped"
            bat "net stop MySQL || echo MySQL already stopped"
        }
        success {
            echo "Pipeline completed successfully!"
        }
        failure {
            echo "Pipeline failed!"
            mail to: 'a00320733@student.tus.ie, a00317717@student.tus.ie, a00322305@student.tus.ie, a00320562@student.tus.ie',
            subject: "Build Failed: ${env.JOB_NAME} #${env.BUILD_NUMBER}", 
            body: "Check Jenkins logs: ${env.BUILD_URL}" 
        }
    }
}
