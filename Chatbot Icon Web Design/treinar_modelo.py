import pandas as pd
import string
import joblib
from sklearn.feature_extraction.text import CountVectorizer
from sklearn.naive_bayes import MultinomialNB
import unicodedata

# ✅ Limpa o CSV com mais de uma vírgula por linha
with open("dados1.csv", encoding="latin1") as f_in, open("dados.csv", "w", encoding="latin1") as f_out:
    for i, line in enumerate(f_in, 1):
        if line.count(",") == 1:
            f_out.write(line)
        else:
            print(f"⚠️ Linha {i} ignorada (muitas vírgulas): {line.strip()}")

# ✅ Função para remover acentos
def remover_acentos(texto):
    return ''.join(
        c for c in unicodedata.normalize('NFD', texto)
        if unicodedata.category(c) != 'Mn'
    )

# ✅ Função de pré-processamento
def preprocess(text):
    text = remover_acentos(text.lower())
    text = text.translate(str.maketrans('', '', string.punctuation))
    return text

# ✅ Carregar os dados já limpos
df = pd.read_csv("dados1.csv", encoding="latin1", sep=",", header=None, names=["frase", "intencao"])
df['frase'] = df['frase'].apply(preprocess)

# ✅ Vetorizar e treinar
vectorizer = CountVectorizer()
X = vectorizer.fit_transform(df['frase'])
y = df['intencao']

modelo = MultinomialNB()
modelo.fit(X, y)

# ✅ Salvar o modelo e o vetor
joblib.dump(modelo, "modelo_intencao.pkl")
joblib.dump(vectorizer, "vectorizer.pkl")

print("✅ Modelo treinado e salvo com sucesso.")

# ✅ Teste com frase
modelo_carregado = joblib.load("modelo_intencao.pkl")
vectorizer_carregado = joblib.load("vectorizer.pkl")

frase_teste = "quero criar um site novo"
frase_teste_processada = preprocess(frase_teste)
entrada = vectorizer_carregado.transform([frase_teste_processada])
intencao_prevista = modelo_carregado.predict(entrada)[0]

print(f"🧠 Intenção detectada: {intencao_prevista}")
