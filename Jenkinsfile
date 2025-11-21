/*
Any variables that need to be changed when moving between environments should be defined in the environment section.
You may set up jenkins to get the script from the repository, or you specify it directly in the pipeline configuration.
If you change the variables, know that jenkins will be pulling the version of this file thats in the repository, not your changed one. You can comment out the 'checkout code' stage, and configure jenkins to use the local version of this file instead, or just specify the script directly in jenkins.
Comment out any stages that involve services not used in your setup (eg sonarcloud analysis) or modify them to fit your environment.

Note: The git branch may need to be changed depending on which branch you want to build from.
*/

pipeline {
    agent any

    environment {
        // ---- PROJECT PATHS ----
        PHP = 'C:\\xampp\\php\\php_new\\php.exe'   // Path to PHP executable
        XAMPP = 'C:\\xampp'                        // XAMPP main directory
        HTDOCS = 'C:\\xampp\\htdocs\\projectEngineering\\TicketMaster'  // Deployment folder

        // ---- MYSQL PATHS ----
        MYSQL = 'C:\\xampp\\mysql\\bin\\mysql.exe' // MySQL executable
        MYSQL_HOST = '127.0.0.1'
        MYSQL_PORT = '3307'

        // ---- DB NAMES ----
        TEST_DB = 'ticketmaster_test'
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
                dir("${HTDOCS}") {
                    script {
                        if (fileExists('composer.json')) {
                            echo "Installing dependencies with Composer..."
                            bat "composer install --no-interaction --no-progress --ansi"
                        } else {
                            echo "No composer.json found. Skipping dependency installation."
                        }
                    }
                }
            }
        }

        stage('Test') {
            steps {
                echo "Starting Apache and MySQL services..."
                bat "net start Apache2.4 || echo Apache already running"
                bat "net start MySQL || echo MySQL already running"

                echo "Preparing fresh test database: ${TEST_DB} ..."
                bat """
                    "${MYSQL}" --host=${MYSQL_HOST} --port=${MYSQL_PORT} -u root -e "DROP DATABASE IF EXISTS ${TEST_DB}; CREATE DATABASE ${TEST_DB};"
                """

                dir("${HTDOCS}") {
                    echo "Importing test schema..."
                    bat """
                        "${MYSQL}" --host=${MYSQL_HOST} --port=${MYSQL_PORT} -u root ${TEST_DB} < app\\config\\test_database.sql
                    """

                    echo "Ensuring coverage and test result folders exist..."
                    bat "if not exist build\\coverage mkdir build\\coverage"

                    echo "Running PHPUnit tests..."
                    bat "\"${PHP}\" vendor\\bin\\phpunit --configuration phpunit.xml --coverage-clover build\\coverage\\clover.xml"
                }
            }
        }

        stage('Mutation Testing (Infection)') {
            steps {
                dir("${HTDOCS_PATH}") {
                    echo "Running Infection mutation tests..."
                    // Ensure infection report directory exists
                    bat "if not exist build\\infection mkdir build\\infection"
                    // Run Infection
                    bat "\"${PHP}\" vendor\\bin\\infection --configuration=infection.json5 --min-msi=60 --min-covered-msi=80"
                }
            }
        }

        stage('SonarCloud Analysis') {
            steps {
                withCredentials([string(credentialsId: 'SONAR_TOKEN', variable: 'SONAR_TOKEN')]) {
                    dir("${HTDOCS}") {
                        withSonarQubeEnv('SonarCloud') {
                            echo "Running SonarCloud analysis using sonar-project.properties..."
                            // Pass the Clover file for coverage
                            bat "sonar-scanner -Dsonar.login=%SONAR_TOKEN% -Dsonar.php.coverage.reportPaths=build\\coverage\\clover.xml
                            -Dsonar.php.infection.reportPath=build/infection/infection-log.txt
"
                        }
                    }
                }
            }
        }

        stage('Deploy') {
            steps {
                echo "Deploying application to XAMPP htdocs..."
                bat "xcopy /Y /E /I . \"${HTDOCS}\\\""
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
            // mail to: 'a00320733@student.tus.ie, a00317717@student.tus.ie, a00322305@student.tus.ie, a00320562@student.tus.ie',
            // subject: "Build Failed: ${env.JOB_NAME} #${env.BUILD_NUMBER}", 
            // body: "Check Jenkins logs: ${env.BUILD_URL}" 
        }
    }
}
