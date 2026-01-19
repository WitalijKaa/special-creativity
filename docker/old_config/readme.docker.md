# how to?

### (and of course u need (for local LLM) something like nvidia 4060)

### if u dont have tokens only "Eternity creation Engine" (web) will work (no LLM functions for poetry)

## u need HF_TOKEN with permissions to use local models like
- (france) https://huggingface.co/mistralai/Mistral-7B-Instruct-v0.3
- (meta) https://huggingface.co/meta-llama/Llama-3.1-8B-Instruct
- (google) https://huggingface.co/google/gemma-7b-it

## u need AO_TOKEN with paid developer account to use GPT models
https://platform.openai.com/settings/organization/api-keys

### u need one of that tokens, or both

### put that tokens to /THIS_PROJECT/docker/.env file

```
HF_TOKEN=hf_...lalala
OA_TOKEN=sk-...tututu
```

## NEXT

#### cd /THIS_PROJECT
#### docker compose -f ./docker/docker-compose.yml up -d

----

and wait long long long time... :)

and after you will wait long long long time when u will first-time use any local LLM it first should download weights to your local machine to hugging_face_cache volume

so again

wait long long long time... very long... :)

----

works for (and maybe others) Windows 11 Home + Docker Desktop + 4060 + nvidia app drivers + tokens

----

# just my Docker commands

----

#### docker compose -f ./docker/docker-compose.yml -p sc_42 up -d
#### docker compose watch
#### docker compose -f ./docker/docker-compose.yml config
