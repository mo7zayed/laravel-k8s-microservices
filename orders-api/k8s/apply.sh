sh k8s/docker/build.sh;
kubectl delete -f k8s/jobs/migrate.yml;
kubectl apply -R -f k8s;
kubectl rollout restart deployment orders-api orders-horizon;
