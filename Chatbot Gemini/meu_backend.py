from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
import google.generativeai as genai
import unidecode  # Para remover acentos

# Configura sua chave da API Gemini

genai.configure(api_key="digite_sua_chave_api_aqui")

# Inicia conversa com contexto personalizado
chat = genai.GenerativeModel("gemini-2.0-flash").start_chat(history=[
    {
        "role": "user",
        "parts": [
           "Você é um assistente especializado em fornecer respostas factuais, com base em fontes oficiais, registros históricos e dados verificados. "
            "Você está integrado a um sistema que só aceita respostas confiáveis. Portanto, é fundamental que você **não invente** dados, **não suponha** e **não generalize**. "
            "Se não tiver certeza de uma informação específica, **diga explicitamente que não sabe** ou que a informação não está confirmada, sugerindo buscar fontes oficiais (como FIA, COI, IBGE, etc.). "
            "Evite linguagem subjetiva e especulativa. Priorize datas, nomes, eventos, estatísticas e fontes concretas. "
            "Responda de forma direta, clara e objetiva. Quando apropriado, forneça a fonte oficial da informação (como nome de entidade, ano, local). "
            "Evite responder com: 'provavelmente', 'acredita-se', 'em geral', 'normalmente', 'em média', etc.\n"
            "Se a pergunta não for clara ou for ambígua, peça mais detalhes ao invés de assumir a intenção do usuário."
            
        ]
    },
    {
        "role": "model",
        "parts": [
            "Entendido. Responderei apenas com informações confiáveis e verificadas, evitando especulações."
        ]
    }
])

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

class Mensagem(BaseModel):
    texto: str

# Função para normalizar texto (remover acentos e minúsculas)
def normalize(texto: str) -> str:
    return unidecode.unidecode(texto.lower())

# Dicionário com palavras-chave e respostas factuais verificadas
fatos_confiaveis = {
   
     "gp japao senna": (
        "Ayrton Senna venceu o GP do Japão de Fórmula 1 **duas vezes**: em 1988 e 1993."
    ),
     "gp australia senna": (
        "Ayrton Senna venceu o GP da Austrália de Fórmula 1 **duas vezes**: em 1991 e 1993."
    ),
     "gp monza senna": (
        "Ayrton Senna venceu o GP de Monza de Fórmula 1 **duas vezes**: em 1990 e 1992."
    ),
     "gp san marino senna": (
        "Ayrton Senna venceu o GP de San Marino de Fórmula 1 **3 vezes**: em 1988 , 1989 e 1991."
    )
    
}

@app.post("/mensagem")
async def receber_mensagem(msg: Mensagem):
    pergunta = msg.texto.strip()
    pergunta_norm = normalize(pergunta)

    for chave, resposta in fatos_confiaveis.items():
        palavras = chave.split()
        if all(palavra in pergunta_norm for palavra in palavras):
            return {
                "resposta": resposta + "\n\n✅ Esta informação foi verificada manualmente e é baseada em fontes oficiais."
            }

    # Se não encontrar no banco de fatos, usa a IA como fallback
    try:
        resposta = chat.send_message(pergunta)
        resposta_final = resposta.text + "\n\n⚠️ Esta resposta foi gerada por IA e pode conter imprecisões factuais."
        return {"resposta": resposta_final}
    except Exception as e:
        return {"erro": str(e)}
