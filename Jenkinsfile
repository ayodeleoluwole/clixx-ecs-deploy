pipeline {
    agent any

    environment {
        PATH = "${PATH}:${getSonarPath()}:${getDockerPath()}:${getTerraformPath()}"
        VERSION = "1.0.${BUILD_NUMBER}"
        RUNNER = "OLUWOLE"
    }

     stages {


        stage ('Sonarcube Scan') {
        steps {
         script {
          scannerHome = tool 'Sonar-inst'
        }
        withCredentials([string(credentialsId: 'SONAR_TOKEN', variable: 'SONAR_TOKEN')]){
        withSonarQubeEnv('SonarQubeScanner') {
          sh " ${scannerHome}/bin/sonar-scanner \
          -Dsonar.projectKey=Clixx-Application   \
          -Dsonar.token=${SONAR_TOKEN} "
        }
        }
        }

     }

 stage('Quality Gate') {
            steps {
                timeout(time: 3, unit: 'MINUTES') {
                    waitForQualityGate abortPipeline: true
            }
            }
        }

          stage ('Build Docker Image') {
          steps {
            slackSend (color: '#FFFF00', message: "BUILDING DOCKER IMAGE by '${env.RUNNER}': Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
             
            // script{
            //  dockerHome= tool 'docker-inst'
            // }
            //  sh "${dockerHome}/bin/docker build . -t clixx-image:$VERSION "
            sh "docker build . -t clixx-image:$VERSION "
          }
        }

        stage ('Starting Docker Image') {
          steps {
            slackSend (color: '#FFFF00', message: "STARTING DOCKER IMAGE by '${env.RUNNER}': Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
             
            sh '''
              if ( docker ps|grep clixx-cont ) then
                 echo "Docker image exists, killing it"
                 docker stop clixx-cont
                 docker rm clixx-cont
                 docker run --name clixx-cont  -p 80:80 -d clixx-image:$VERSION
              else
                 docker run --name clixx-cont  -p 80:80 -d clixx-image:$VERSION 
              fi
            '''
          }
        }




        stage ('Tear Down CliXX Docker Image') {
              steps {
                slackSend (color: '#FFFF00', message: "TEARING DOWN DOCKER IMAGE by '${env.RUNNER}': Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
             
                script {
                def userInput = input(id: 'confirm', message: 'Please Test CliXX Image Deployment. Should I delete now?', parameters: [ [$class: 'BooleanParameterDefinition', defaultValue: false, description: 'Tear Down App', name: 'confirm'] ])
             }
             sh " docker stop clixx-cont "
             sh " docker rm  clixx-cont "
           }
        }



        stage ('Log Into ECR and push the newly created Docker') {
          steps {
            slackSend (color: '#FFFF00', message: "PUSHING LATEST IMAGE TO ECR by '${env.RUNNER}': Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
             script {
                def userInput = input(id: 'confirm', message: 'Push Image To ECR?', parameters: [ [$class: 'BooleanParameterDefinition', defaultValue: false, description: 'Push to ECR?', name: 'confirm'] ])
             }
  
              sh '''
                STATE=$(aws s3 cp s3://mystatefile-clixxretail/base-infrastructure/terraform.tfstate -)
                ECR_URI=$(echo $STATE | python3 -c "import sys,json; d=json.load(sys.stdin); print(d['outputs']['ecr_repository_url']['value'])")

                aws ecr get-login-password --region us-east-2 | docker login --username AWS --password-stdin ${ECR_URI}
                docker tag clixx-image:$VERSION ${ECR_URI}:clixx-image-$VERSION
                docker tag clixx-image:$VERSION ${ECR_URI}:latest
                docker push ${ECR_URI}:clixx-image-$VERSION
                docker push ${ECR_URI}:latest
              '''
          
          }
        }


        stage ('Configure RDS Database for Production') {
          steps {
            slackSend (color: '#FFFF00', message: "CONFIGURING DATABASE FOR PRODUCTION by '${env.RUNNER}': Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
              withCredentials([string(credentialsId: 'DB_USER_NAME', variable: 'DB_USER_NAME'), string(credentialsId: 'DB_PASSWORD', variable: 'DB_PASSWORD'), string(credentialsId: 'DB_NAME', variable: 'DB_NAME'), string(credentialsId: 'RDS_ENDPOINT', variable: 'RDS_ENDPOINT')]){
              sh '''
               USERNAME=${DB_USER_NAME}
               PASSWORD=${DB_PASSWORD}
               DBNAME=${DB_NAME}
               RDS_ENDPOINT=${RDS_ENDPOINT}


              # Pull outputs from remote state instead of local terraform
              STATE=$(aws s3 cp s3://mystatefile-clixxretail/base-infrastructure/terraform.tfstate -)
              LOAD_BALANCER=$(echo $STATE | python3 -c "import sys,json; d=json.load(sys.stdin); print(d['outputs']['lb_endpoint']['value'])")

               echo "use $DBNAME;" > $WORKSPACE/db.setup
               echo "UPDATE wp_options SET option_value = 'http://$LOAD_BALANCER' WHERE option_value LIKE 'http%';">> $WORKSPACE/db.setup
               mysql -u $USERNAME --password=$PASSWORD -h $RDS_ENDPOINT  -D  $DBNAME < $WORKSPACE/db.setup

              '''
              }
          }
        }



        stage ('Deploy to ECS') {
        steps {
          slackSend (color: '#FFFF00', message: "DEPLOYING APPLICATION LATEST VERSION VIA ECS  by '${env.RUNNER}': Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
             
          script {
            def userInput = input( id: 'confirm', message: 'Deploy latest image to ECS?', parameters: [[$class: 'BooleanParameterDefinition', defaultValue: false, description: 'Deploy to ECS', name: 'confirm']])
          }

              sh '''

                # Pull outputs from remote state instead of local terraform
                STATE=$(aws s3 cp s3://mystatefile-clixxretail/base-infrastructure/terraform.tfstate -)
                ECS_CLUSTER_NAME=$(echo $STATE | python3 -c "import sys,json; d=json.load(sys.stdin); print(d['outputs']['ecs_cluster_name']['value'])")
                ECS_SERVICE_NAME=$(echo $STATE | python3 -c "import sys,json; d=json.load(sys.stdin); print(d['outputs']['ecs_service_name']['value'])")

                AWS_REGION=us-east-2

                aws ecs update-service \
                --cluster ${ECS_CLUSTER_NAME} \
                --service ${ECS_SERVICE_NAME} \
                --force-new-deployment \
                --region ${AWS_REGION}

                echo "ECS successfully redeployed latest image"
              '''
              slackSend (color: '#FFFF00', message: "APPLICATION DEPLOYMENT COMPLETED by '${env.RUNNER}': Job '${env.JOB_NAME} [${env.BUILD_NUMBER}]' (${env.BUILD_URL})")
             
       }
    }

  }

}


def getSonarPath(){
        def SonarHome= tool name: 'Sonar-inst', type: 'hudson.plugins.sonar.SonarRunnerInstallation'
        return SonarHome
    }
def getDockerPath(){
        def DockerHome= tool name: 'docker-inst', type: 'dockerTool'
        return DockerHome
    }
    
 def getTerraformPath(){
        def tfHome= tool name: 'terraform-14', type: 'terraform'
        return tfHome
    }