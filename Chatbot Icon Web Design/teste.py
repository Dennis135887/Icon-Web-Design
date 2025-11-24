from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import unicodedata
import string

app = Flask(__name__)
CORS(app)  # Permite requisições de outras origens (como seu HTML)

# Carrega modelo e vetor
modelo = joblib.load("modelo_intencao.pkl")
vectorizer = joblib.load("vectorizer.pkl")

def remover_acentos(texto):
    return ''.join(
        c for c in unicodedata.normalize('NFD', texto)
        if unicodedata.category(c) != 'Mn'
    )

def preprocess(texto):
    texto = remover_acentos(texto.lower())
    texto = texto.translate(str.maketrans('', '', string.punctuation))
    return texto

# Respostas personalizadas
respostas = {
   
    "site": """Criamos sites institucionais, profissionais e personalizados.
    <ul>
  <li>Criação de sites institucionais e landing pages</li>
  <li>Páginas de vendas otimizadas para conversão</li>
  <li>Hospedagem de sites com suporte técnico</li>
  <li>Suporte e manutenção contínua</li>
  <li>Design gráfico e identidade visual</li>
  <li>Cartão de visita digital interativo</li>
    </ul>
    Conte conosco para desenvolver o seu!""",
    "landing": "Desenvolvemos landing pages focadas em conversão e geração de leads.",
    "cartao": "Fazemos cartões de visita digitais e interativos para você compartilhar com facilidade.",
    "servicos": "Oferecemos: criação de sites, páginas de vendas, identidade visual, SEO, gestão de redes sociais, Google Ads e suporte técnico.",
    "precos": "Nossos sites custam a partir de R$199, dividido em até 6x. Consulte para outros serviços ou pacotes.",
    "pagamento": "Aceitamos Pix, cartão de crédito, boleto bancário e parcelamentos.",
    "prazo": "O prazo médio de entrega é entre 7 a 10 dias úteis.",
    "google_ads": "Sim, oferecemos serviços de tráfego pago com Google Ads. Criamos, otimizamos e gerenciamos suas campanhas para melhor performance.",
    "redes_sociais": "Sim, trabalhamos com redes sociais! Fazemos gestão de conteúdo para redes sociais como Instagram e Facebook, criação de artes, agendamento de posts e muito mais. Quer saber mais detalhes?",
    "portfolio": "Claro! Você pode ver nossos trabalhos no portfólio em nosso site ou solicitar por WhatsApp.",
    "suporte": "Estamos aqui para te ajudar. Descreva o problema e nosso suporte técnico entrará em contato.",
    "atualizacao_site": "Atualizamos seu site com novos conteúdos, imagens ou design. Fale conosco para iniciar.",
    "contato": "Você pode nos contatar pelo WhatsApp ou e-mail informados em nosso site.",
    "faq": "Você pode perguntar sobre preços, serviços, prazos e suporte. Estou aqui para ajudar!",
    "design_grafico": "Criamos logotipos, artes, identidade visual, flyers e mais para sua marca.",
    "cancelar": "Para cancelar o serviço, entre em contato conosco. Verificaremos o status do seu contrato.",
    "seo": "Fazemos otimização (SEO) para melhorar o posicionamento do seu site no Google.",
    "orcamento": "Envie os detalhes do seu projeto e faremos um orçamento personalizado para você.",
    "parceria": "Temos interesse em parcerias comerciais. Fale conosco para conversar sobre oportunidades.",
}

    

@app.route('/chat', methods=['POST'])
def chat():
    data = request.get_json()
    frase = data.get("message", "")
    frase = preprocess(frase)

    if not frase:
        return jsonify({"reply": "Por favor, digite uma mensagem válida."}), 400

    X_input = vectorizer.transform([frase])
    intencao = modelo.predict(X_input)[0]
    resposta = respostas.get(intencao, "Desculpe, não entendi sua pergunta.")

    return jsonify({"reply": resposta})

if __name__ == "__main__":
    app.run(debug=True)
