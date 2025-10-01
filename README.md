# CliXX ECS Deploy


## Overview

This is the application deployment pipeline for CliXX Retail. It takes the WordPress application code, scans it with SonarQube, builds a Docker image, tests it locally, pushes it to ECR, configures the RDS database with the live ALB endpoint, and forces a new ECS Fargate deployment; all in a single pipeline run. Infrastructure is provisioned separately in a different pipeline. This pipeline consumes that infrastructure.

---

## Prerequisites

- Jenkins with the following plugins: Slack Notification, Credentials Binding, SonarQube Scanner
- Docker installed on the Jenkins agent (`docker-inst` tool configured in Jenkins)
- SonarQube server configured in Jenkins (`Sonar-inst` tool, `SonarQubeScanner` environment)
- Terraform configured in Jenkins (`terraform-14` tool)
- AWS CLI configured on the Jenkins agent
- Python 3 available on the Jenkins agent
- Jenkins credentials store configured with: `SONAR_TOKEN`, `DB_USER_NAME`, `DB_PASSWORD`, `DB_NAME`, `RDS_ENDPOINT`
- Infrastructure from `clixx-fargate-infra` must be deployed and state stored in S3

---

## Pipeline Stages

| Stage | Description |
|---|---|
| **SonarQube Scan** | Runs static code analysis on the WordPress application against the `Clixx-Application` project key |
| **Quality Gate** | Waits up to 3 minutes for SonarQube quality gate result — aborts pipeline on failure |
| **Build Docker Image** | Builds a versioned Docker image (`clixx-image:1.0.BUILD_NUMBER`) from the Dockerfile |
| **Start Docker Image** | Runs the container locally on port 80; stops and replaces any existing container |
| **Tear Down Docker Image** | Manual approval gate to allow testing, then stops and removes the local container |
| **Push to ECR** | Manual approval gate, then authenticates to ECR, tags and pushes both versioned and `latest` image |
| **Configure RDS for Production** | Pulls ALB endpoint from remote Terraform state and updates the WordPress site URL in RDS |
| **Deploy to ECS** | Manual approval gate, then forces a new ECS Fargate deployment with the latest image |


---


## Usage

**Via Jenkins** : point your Jenkins job at this repo. The pipeline handles everything end to end.

```
1. Pipeline triggers and runs SonarQube scan
2. Quality gate result is evaluated — pipeline aborts on failure
3. Docker image is built and tagged with the build number
4. Container runs locally on port 80 for manual testing
5. Approve the teardown prompt once testing is complete
6. Approve the ECR push prompt — image is tagged and pushed
7. RDS site URL is updated with the ALB endpoint
8. Approve the ECS deploy prompt — Fargate pulls the latest image
```

---

## Project Structure

```
clixx-ecs-deploy/
├── wp-admin/               # WordPress admin
├── wp-content/             # WordPress themes, plugins, uploads
├── wp-includes/            # WordPress core includes
├── wp-config.php           # WordPress configuration
├── Dockerfile              # Container image definition
├── Jenkinsfile             # Jenkins CI/CD pipeline definition
└── README.md
```

---


## Slack Notifications

The pipeline sends Slack notifications at every active stage:
- Docker image build start
- Docker container start
- Docker container teardown
- ECR push start
- RDS configuration start
- ECS deployment start and completion
